<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\grosProduit;
use App\Models\ProduitType;
use DateTime; // Importez la classe DateTime en haut de votre fichier
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FactureExport;
use Maatwebsite\Excel\Facades\Excel;


class FactureController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();
        
        $factures = Facture::all();
        $client = Client::all();

        // Creez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures
        ->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->client . $facture->totalHT . $facture->emplacement;
        })
        ->sortByDesc('date');

            
        return view('Factures.index', compact('factures', 'codesFacturesUniques'));
    }
    
    /**
     * Imprimer une facture xprint
     */
    public function impression($code, $date)
    {
        // Vous récupérez la facture en fonction du code et de la date
        $factures = Facture::where('date', $date)->where('code', $code)->get();
        
        // Retournez la vue dédiée à l'impression
        return view('Factures.impression', compact('factures', 'date', 'code'));
    }

    /**
     * Rechercher une facture sur la liste des facture
     */
     public function recherche(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        // Initialiser les dates de début et de fin
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();
        
        if($dateDebut > $dateFin){
            return back()->with('error_message','La date début ne peut pas être superieur à la date fin');
        }

        // Créer une requête pour les factures filtrées par date
        $query = Facture::query();
        
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $dateDebut);
        }
        
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $dateFin);
        }
        
        // Récupérer les factures filtrées par date
        $factures = $query->get();
        
        // Récupérer la somme du montantFinal pour l'utilisateur connecté et la date filtrée
        $sommeMontant = $factures->where('user_id', $user->id)->sum('montantFinal');
        
        // Créez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures
            ->unique(function ($facture) {
                return $facture->code . $facture->date . $facture->client . $facture->totalHT . $facture->emplacement;
            })
            ->sortByDesc('date');

            $totalTTCType1 = $codesFacturesUniques
            ->filter(fn($facture) => $facture->produitType_id == 1)
            ->sum('montantFinal');
        
        $totalTTCType3 = $codesFacturesUniques
            ->filter(fn($facture) => $facture->produitType_id == 2)
            ->sum('montantFinal');
        
            $date = Carbon::now();
        
        return view('Factures.recherche', compact('factures','totalTTCType1','totalTTCType3','date','codesFacturesUniques', 'dateDebut', 'dateFin'));
    }

    /**
     * Afficher les details d'une facture
     */
    public function details($code, $date)
    {
        $factures = Facture::where('code',$code)->get();
        
        return view('Factures.details', compact('factures','date','code'));
    }

    /**
     * Annuler une facture
     */
    public function annuler(Request $request)
    {
        
       $code=$request->factureCode;
        $factures = Facture::select('produit', 'quantite')->where('code', $code)->get();
        foreach ($factures as $facture) {
            //c'est la tu feras le jeu
            $produit = grosProduit::where('libelle', $facture->produit)->first();

            if ($produit) {
                $nouvelleQuantite = $produit->quantite + $facture->quantite - $facture->quantite; // Mettez à jour la nouvelle quantité
        
                // Assurez-vous de mettre à jour le produit avec la nouvelle quantité correcte
                $produit->quantite = $nouvelleQuantite;
                $produit->save();
            }
        }
        // Suppression de toutes les factures avec le code spécifié
        Facture::where('code', $code)->delete();

        return back()->with('success_message', 'La facture a été annulée avec succès.');
    }

    /**
     * Afficher la page pour enregistrer une facture
     */
    public function create()
    {
        $clients = Client::all();
        $produits = grosProduit::all();
        $user=Auth::user();
        $produitTypes = ProduitType::all();
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
            ->groupBy('produit')
            ->get();

        // Créez un tableau associatif pour stocker la quantité de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $stockActuel;
            } else {
                // Si la quantité de sortie n'est pas définie, le stock actuel est égal à la quantité totale
                $produit->stock_actuel = $produit->quantite;
            }
        }

        return view('Factures.create', compact('clients','produits','produitTypes','user'));
    }

    /**
     * Enregistrer la facture
    */
    public function store(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('success_message', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $donnees = json_decode($request->input('donnees'));
        $client_id_full = $request->client;
        $parts = explode(' ', $client_id_full);
        $client_id = $parts[0] ?? null;
       $client_nom = !empty($parts) ? implode(' ', array_slice($parts, 1)) : 'Inconnu';
        $dateString = $request->date;
        $date = new DateTime($dateString);
        $totalHT = $request->totalHT;
        $totalTVA = $request->totalTVA;
        $totalTTC = $request->totalTTC;
        $montantPaye = $request->montantPaye;
        $monnaie = $request->monnaie;
        $remise = $request->remise;
        $montantFinal = $request->montantFinal;
        $montantRendu = $request->montantRendu;
        $produitType = $request->produitType;
        $idUser = Auth::user()->id;

        // Récupérer le prochain numéro de facture
        $dernierNumero = DB::table('factures')->max('id'); 
        $nouveauNumero = $dernierNumero ? $dernierNumero + 1 : 1;
        $code = str_pad($nouveauNumero, 6, '0', STR_PAD_LEFT);

        // 🔒 Étape 1 : Vérification du stock pour TOUS les produits
       // Étape 1 : Regrouper les produits et totaliser les quantités demandées
        $quantitesParProduit = [];

        foreach ($donnees as $donnee) {
            if (isset($quantitesParProduit[$donnee->produit])) {
                $quantitesParProduit[$donnee->produit] += $donnee->quantite;
            } else {
                $quantitesParProduit[$donnee->produit] = $donnee->quantite;
            }
        }

        // Étape 2 : Vérifier les stocks pour chaque produit unique
        foreach ($quantitesParProduit as $produit => $quantiteTotaleDemandee) {
            $produitActuel = grosProduit::where('libelle', $produit)->first();

            if (!$produitActuel) {
                return response()->json([
                    'error_message' => "Le produit {$produit} n'existe pas en stock."
                ], 500);
            }

            $totalVendu = Facture::where('produit', $produit)->sum('quantite');
            $stockReel = $produitActuel->quantite - $totalVendu;

            if ($stockReel < $quantiteTotaleDemandee) {
                return response()->json([
                    'error_message' => "Le stock du produit {$produit} est insuffisant. Stock disponible : {$stockReel}, demandé : {$quantiteTotaleDemandee}."
                ], 500);
            }
        }

        // ✅ Étape 2 : Enregistrement des factures dans une transaction
        try {
            foreach ($donnees as $donnee) {
                // Récupération du produit dans la table gros_produits
                $produit = GrosProduit::where('libelle', $donnee->produit)->first();

                // Vérification si le produitType correspond
                if ($produit && $produit->produitType_id == $produitType) {
                    $produitTypeFinal = $produitType;
                } else {
                    // Si le produitType demandé est 1, on prend 2, sinon on prend 1
                    $produitTypeFinal = ($produitType == 1) ? 2 : 1;
                }


                // Création de la facture
                $facture = new Facture();
                $facture->client = $client_id;
                $facture->client_nom = $client_nom;
                $facture->date = $date;
                $facture->produitType_id = $produitTypeFinal;
                $facture->totalHT = $totalHT;
                $facture->totalTVA = $totalTVA;
                $facture->totalTTC = $totalTTC;
                $facture->montantPaye = $montantPaye;
                $facture->montantRendu = $montantRendu;
                $facture->quantite = $donnee->quantite;
                $facture->produit = $donnee->produit;
                $facture->prix = $donnee->prix;
                $facture->total = $donnee->total;
                $facture->code = $code;
                $facture->user_id = $idUser;
                $facture->reduction = $remise;
                $facture->montantFinal = $montantFinal;
                $facture->monnaie = $monnaie;
                $facture->save();
            }

            return response()->json(['message' => 'Facture enregistrée avec succès'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error_message' => 'Une erreur est survenue lors de l\'enregistrement.'
            ], 500);
        }
    }

    /**
     * Générer pdf des facture (revoir)
    */
    public function pdf($code, $date)
    {
        // Définir la locale de Carbon à français
        Carbon::setLocale('fr');
        // Obtenir la date et l'heure actuelles
        $currentDateTime = Carbon::now();
        // Formater la date et l'heure dans le format souhaité
        $formattedDate = $currentDateTime->translatedFormat('l d F Y à H\h i\m\i\n s\s');
        $factures = Facture::where('code', $code)
                            ->where('date', $date)
                            ->with('user')
                            ->get();

        $pdf = Pdf::loadView('Factures.factureVente_pdf', [
            'factures' => $factures,
            'date' => $date,
            'code' => $code,
            'formattedDate'=>$formattedDate
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Facture-' . $code . '.pdf');
    }   


    /**
     * PDF DE LA sommation des factures
     */
     public function genererPDF(Request $request)
    {

        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');

        // Récupération des factures uniques
        $codesFacturesUniques = Facture::with('user')
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->select('code','date','client_nom','totalTTC','montantPaye','montantRendu','montantFinal','produitType_id','user_id')
            ->distinct()
            ->get();

        // Totaux spécifiques
        $totalTTCType1 = Facture::whereBetween('date', [$dateDebut, $dateFin])
            ->where('produitType_id', 1)
            ->sum('montantFinal');

        $totalTTCType3 = Facture::whereBetween('date', [$dateDebut, $dateFin])
            ->where('produitType_id', 2)
            ->sum('montantFinal');

        // Génération du PDF
        $pdf = Pdf::loadView('Factures.sommationFacture_pdf', compact('codesFacturesUniques','dateDebut','dateFin','totalTTCType1','totalTTCType3'));

        // Retourne le fichier à télécharger
        return $pdf->download('facture_' . $dateDebut . '_au_' . $dateFin . '.pdf');
    }

    /**
     * EXCEL pour sommation
     */
     public function genererExcel(Request $request)
    {
        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');

        $codesFacturesUniques = Facture::with('user')
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->select('code','date','client_nom','totalTTC','montantPaye','montantRendu','montantFinal','produitType_id','user_id')
            ->distinct()
            ->get();

        return Excel::download(new FactureExport($codesFacturesUniques), 'facture_'.$dateDebut.'_'.$dateFin.'.xlsx');
    }

}
