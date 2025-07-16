<?php

namespace App\Http\Controllers;

use App\Exports\FactureAchatExport;
use App\Models\FactureAchat;
use App\Models\grosProduit;
use App\Models\ProduitType;
use App\Models\Societe;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;




class FactureAchatController extends Controller
{
    //
    public function index()
    {

        $factures = FactureAchat::all();

        // Creez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures
        ->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->totalTTC . $facture->totalHT;
        })
        ->sortByDesc('date');

        $societes= Societe::all();
            
        return view('Factures.FactureAchats.index', compact('codesFacturesUniques','societes'));
    }

    /**
     * Afficher la page pour enregistrer une facture d'achat
     */
    public function create()
    {
        $produitTypes = ProduitType::all();
        $produits = grosProduit::all();
        $societes = Societe::all();

        return view('Factures.FactureAchats.create', compact('societes','produitTypes','produits'));
    }

    /**
     * Enregistrer la facture d'achat
    */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('success_message', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $donnees = json_decode($request->input('donnees'));
        $societe_id = $request->societe;
        $dateString = $request->date;
        $totalAchat = $request->totalAchat;
        $totalVente = $request->totalVente;
        $totalBenefice = $request->totalBenefice;
        $produitType = $request->produitType;
        $user_id = Auth::id();
        $date = new DateTime($dateString);

        // Générer un code facture unique
        $dernierNumero = DB::table('facture_achats')->max('id');
        $code = str_pad($dernierNumero ? $dernierNumero + 1 : 1, 6, '0', STR_PAD_LEFT);

        try {
            foreach ($donnees as $donnee) {
                // Créer la facture
                $facture = new FactureAchat();
                $facture->fill([
                    'societe_id' => $societe_id,
                    'date' => $date,
                    'totalAchat' => $totalAchat,
                    'totalVente' => $totalVente,
                    'totalBenefice' => $totalBenefice,
                    'quantite' => $donnee->quantite,
                    'benefice' => $donnee->benefice,
                    'produit' => $donnee->produit,
                    'prix' => $donnee->prixAchat,
                    'prixVente' => $donnee->prixVente,
                    'total' => $donnee->total,
                    'code' => $code,
                    'produitType_id' => $produitType,
                    'user_id' => $user_id
                ]);
                $facture->save();

                // Chercher le produit selon le type donné, sinon essayer l’autre type
                $produit = grosProduit::where('libelle', $donnee->produit)
                    ->where('produitType_id', $produitType)
                    ->first();

                if (!$produit) {
                    $autreType = $produitType == 1 ? 2 : 1;
                    $produit = grosProduit::where('libelle', $donnee->produit)
                        ->where('produitType_id', $autreType)
                        ->first();
                } else {
                    $autreType = $produitType; // Si produit trouvé du 1er coup, on garde le type d’origine
                }

                // Si le produit existe
                if ($produit) {
                    // Enregistrer dans le stock
                    $stock = new Stock();
                    $stock->fill([
                        'libelle' => $donnee->produit,
                        'quantite' => $donnee->quantite,
                        'date' => $date,
                        'produitType_id' => $autreType,
                        'user_id' => $user_id
                    ]);
                    $stock->save();

                    // Mise à jour du produit (quantité + prix)
                    $produit->update([
                        'quantite' => $produit->quantite + $donnee->quantite,
                        'prix' => $donnee->prixVente,
                        'prixAchat' => $donnee->prixAchat,
                    ]);
                }
            }

            return response()->json(['message' => "Facture d'achat enregistrée avec succès"], 200);
        } catch (\Exception $e) {
            // Loguer ou afficher l’erreur si besoin
            return response()->json(['error_message' => "Une erreur est survenue lors de l'enregistrement."], 500);
        }
    }

    /**
     * Afficher les details d'une facture
     */
    public function details($code, $date)
    {
        $factures = FactureAchat::where('code',$code)->get();
        
        return view('Factures.FactureAchats.detail', compact('date', 'code', 'factures'));
    }


    /**
     * Rechercher une facture sur la liste des facture
     */
    public function recherche(Request $request)
    {
        // Initialiser les dates
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();

        if ($dateDebut > $dateFin) {
            return back()->with('error_message', 'La date de début ne peut pas être supérieure à la date de fin');
        }

        // Créer une requête pour filtrer par date et société
        $query = FactureAchat::query();

        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $dateDebut);
        }

        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $dateFin);
        }

        if ($request->filled('societe_id')) {
            $query->where('societe_id', $request->societe_id);
        }

        // Récupérer les factures filtrées
        $factures = $query->get();

        // Supprimer les doublons basés sur code, date, totalHT et totalTTC
        $codesFacturesUniques = $factures->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->totalTTC . $facture->totalHT;
        })->sortByDesc('date');

        // Calcul des totaux par type de produit
        $totalTTCType1 = $codesFacturesUniques
            ->filter(fn($facture) => $facture->produitType_id == 2)
            ->sum('totalBenefice');

        $totalTTCType3 = $codesFacturesUniques
            ->filter(fn($facture) => $facture->produitType_id == 1)
            ->sum('totalBenefice');

        $date = Carbon::now();
        $societes = Societe::all();

        return view('Factures.FactureAchats.recherche', compact(
            'societes', 'factures', 'totalTTCType1', 'totalTTCType3', 
            'date', 'codesFacturesUniques', 'dateDebut', 'dateFin', 'request'
        ));
    }

    /**
     * Annuler facture en attente
     */
    public function annuler(Request $request)
    {
        
       $code=$request->factureCode;
      FactureAchat::where('code', $code)->delete();
      
        return back()->with('success_message', 'La facture d\'achat a été annulée avec succès.');
    }

    //Pdf facture achat
    public function pdf($code, $date)
    {
         // Définir la locale de Carbon à français
         Carbon::setLocale('fr');
         // Obtenir la date et l'heure actuelles
         $currentDateTime = Carbon::now();
         // Formater la date et l'heure dans le format souhaité
         $formattedDate = $currentDateTime->translatedFormat('l d F Y à H\h i\m\i\n s\s');
        
        $factures = FactureAchat::where('code', $code)
                            ->where('date', $date)
                            ->with('user')
                            ->get();
    
        $pdf = Pdf::loadView('Factures.FactureAchats.pdf', [
            'factures' => $factures,
            'date' => $date,
            'code' => $code,
            'formattedDate'=>$formattedDate
        ])->setPaper('a4', 'portrait');
    
        return $pdf->download('Facture-' . $code . '.pdf');
    }
    // PDF facture recherche
    public function genererPDF(Request $request)
    {
        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');
        $societeId = $request->get('societe_id');

        // Récupération des factures selon le filtre
        $query = FactureAchat::with(['user','societe'])
            ->whereBetween('date', [$dateDebut, $dateFin]);


        if ($societeId) {
            $query->where('societe_id', $societeId);
        }

        $codesFacturesUniques = $query->select('code','date','societe_id','user_id','produitType_id','totalBenefice','totalAchat','totalVente')
            ->groupBy('code','date','societe_id','user_id','produitType_id','totalBenefice','totalAchat','totalVente')
            ->get();

            //dd($codesFacturesUniques);

        $totalTTCType1 = $codesFacturesUniques
            ->where('produitType_id', 2) // Adapter selon ta convention
            ->sum('totalBenefice');
            

        $totalTTCType3 = $codesFacturesUniques
            ->where('produitType_id', 1) // Adapter selon ta convention
            ->sum('totalBenefice');


        $pdf = Pdf::loadView('Factures.FactureAchats.sommaire_pdf', compact(
            'codesFacturesUniques',
            'dateDebut',
            'dateFin',
            'totalTTCType1',
            'totalTTCType3'
        ));

        return $pdf->download("facture_achats_{$dateDebut}_{$dateFin}.pdf");
    }

    /**
     * EXCEL pour sommation
     */
     public function genererExcel(Request $request)
    {
        $dateDebut = $request->get('dateDebut');
        $dateFin = $request->get('dateFin');
        $societeId = $request->get('societe_id');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new FactureAchatExport($dateDebut, $dateFin, $societeId),
            "facture_achats_{$dateDebut}_{$dateFin}.xlsx"
        );
    }

}
