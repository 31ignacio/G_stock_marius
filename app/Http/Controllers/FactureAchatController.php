<?php

namespace App\Http\Controllers;

use App\Models\FactureAchat;
use App\Models\grosProduit;
use App\Models\ProduitType;
use App\Models\Societe;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



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

        if (Auth::check()) {
            $donnees = json_decode($request->input('donnees'));
            $societe_id = $request->societe;
            $dateString = $request->date;
            $totalAchat = $request->totalAchat;
            $totalVente = $request->totalVente;
            $totalBenefice = $request->totalBenefice;
            $produitType = $request->produitType;

            // Récupérer le dernier numéro de facture enregistré
            $dernierNumero = DB::table('facture_achats')->max('id'); 

            // Si aucune facture, on commence à 1, sinon on incrémente
            $nouveauNumero = $dernierNumero ? $dernierNumero + 1 : 1;

            $code = str_pad($nouveauNumero, 6, '0', STR_PAD_LEFT); // Format avec des zéros (ex: Facture_000001)

            $date = new DateTime($dateString);

        
            try {
                foreach ($donnees as $donnee) {
                    
                    // Création de la facture
                    $facture = new FactureAchat();
                    $facture->societe_id = $societe_id;
                    $facture->date = $date;
                    $facture->totalAchat = $totalAchat;
                    $facture->totalVente = $totalVente;
                    $facture->totalBenefice = $totalBenefice;
                    $facture->quantite = $donnee->quantite;
                    $facture->benefice = $donnee->benefice;
                    $facture->produit = $donnee->produit;
                    $facture->prix = $donnee->prixAchat;
                    $facture->prixVente = $donnee->prixVente;
                    $facture->total = $donnee->total;
                    $facture->code = $code;
                    $facture->produitType_id = $produitType;

                    $facture->user_id = Auth::user()->id;

                    $facture->save();
                }

                return response()->json(['message' => 'Facture d\'achat enregistrée avec succès'], 200);
            } catch (Exception $e) {
                return response()->json(['error_message' => 'Une erreur est survenue lors de l\'enregistrement.'], 500);
            }
                            
        } else {
            return redirect()->route('login')->with('success_message', 'Veuillez vous connecter pour accéder à cette page.');
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
            ->filter(fn($facture) => $facture->produitType_id == 1)
            ->sum('totalTTC');

        $totalTTCType3 = $codesFacturesUniques
            ->filter(fn($facture) => $facture->produitType_id == 3)
            ->sum('totalTTC');

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

}
