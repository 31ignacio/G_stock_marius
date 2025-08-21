<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\grosProduit;
use App\Models\Stock;
use App\Models\User;
use App\Models\StockAttente;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StockExport;
use App\Exports\StockPoissonnerieExport;;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SortieStockExport;
use App\Exports\SortieStockPoissonnerieExport;
use Exception;
use App\Notifications\StockNotification;
use App\Notifications\ValiderNotification;


class StockController extends Controller
{
    /**
     * Affiche le menu du stock detail
     */
    public function index()
    {
        return view('Stocks.index');
    }

    /**
     * Affiche la page des entres de stock divers
     */
    public function entrer()
    {
        $stocks = Stock::where('produitType_id', 2)
        ->orderBy('date', 'desc')
        ->paginate(10);
        $produits = grosProduit::where('produitType_id', 2)->get();

        return view('Stocks.entrer', compact('stocks','produits'));
    }

    /**
     * Affiche la page des entres de stock poissonnerie
     */
    public function entrerPoissonnerie()
    {

        $stocks = Stock::where('produitType_id', 1)
        ->orderBy('date', 'desc')
        ->paginate(10);

        $produits = grosProduit::where('produitType_id', 1)->get();

        return view('Stocks.entrerPoissonnerie', compact('stocks','produits'));
    }
    
    /**
     * Recherche sur la liste des entrés de stock divers
    */
    public function rechercheDetail(Request $request)
    {
        // Initialiser les dates de début et de fin
        $dateDebut = $request->filled('dateDebut') ? Carbon::parse($request->dateDebut)->startOfDay() : now()->startOfDay();
        $dateFin = $request->filled('dateFin') ? Carbon::parse($request->dateFin)->endOfDay() : now()->endOfDay();

        if ($dateDebut > $dateFin) {
            return back()->with('error_message', 'La date de début ne peut pas être supérieure à la date de fin.');
        }

        // Requête avec filtres appliqués (date et produit)
        $stocks = Stock::select('date', 'libelle','user_id', DB::raw('SUM(quantite) as total_quantite'))
            ->where('produitType_id', 2)
            ->when($request->filled('dateDebut'), function ($query) use ($dateDebut) {
                $query->where('date', '>=', $dateDebut);
            })
            ->when($request->filled('dateFin'), function ($query) use ($dateFin) {
                $query->where('date', '<=', $dateFin);
            })
            ->when($request->filled('libelle'), function ($query) use ($request) {
                $query->where('libelle', $request->libelle);
            })
            ->groupBy('date', 'libelle')
            ->orderBy('date', 'asc')
            ->get();

      
        // Récupération des produits à partir des stocks
        $produits = Stock::select('libelle')->distinct()->get();
        $date = Carbon::now();

        return view('Stocks.rechercheDetail', compact('stocks', 'dateDebut', 'dateFin', 'date', 'produits'));
    }

    /** 
     * PDF recherche divers
    */
    public function generatePDF(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $libelle = $request->libelle;

        $query = Stock::query()
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->where('produitType_id',2);


        if ($libelle) {
            $query->where('libelle', $libelle);
        }

        $stocks = $query
            ->selectRaw('date, libelle, SUM(quantite) as total_quantite, user_id')
            ->groupBy('date', 'libelle', 'user_id')
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();

        $date = now();

        $pdf = Pdf::loadView('Stocks.entreepdf', compact('stocks', 'dateDebut', 'dateFin', 'date'));

        return $pdf->download('stock-divers.pdf');
    }

    /**
     * EXCEL divers
     */
    public function exportExcel(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $libelle = $request->libelle;

        $fileName = 'stock-divers-' . now()->format('Y-m-d-H-i') . '.xlsx';

        return Excel::download(new StockExport($dateDebut, $dateFin, $libelle), $fileName);
    }


    /**
     * Recherche sur la liste des entrés de la poissonnerie
    */
    public function recherchePoissonnerie(Request $request){
        
        // Initialiser les dates de début et de fin
        $dateDebut = $request->filled('dateDebut') ? Carbon::parse($request->dateDebut)->startOfDay() : now()->startOfDay();
        $dateFin = $request->filled('dateFin') ? Carbon::parse($request->dateFin)->endOfDay() : now()->endOfDay();

        if ($dateDebut > $dateFin) {
            return back()->with('error_message', 'La date de début ne peut pas être supérieure à la date de fin.');
        }

        // Requête avec filtres appliqués (date et produit)
        $stocks = Stock::select('date', 'libelle','user_id', DB::raw('SUM(quantite) as total_quantite'))
            ->where('produitType_id', 1)
            ->when($request->filled('dateDebut'), function ($query) use ($dateDebut) {
                $query->where('date', '>=', $dateDebut);
            })
            ->when($request->filled('dateFin'), function ($query) use ($dateFin) {
                $query->where('date', '<=', $dateFin);
            })
            ->when($request->filled('libelle'), function ($query) use ($request) {
                $query->where('libelle', $request->libelle);
            })
            ->groupBy('date', 'libelle')
            ->orderBy('date', 'asc')
            ->get();

        $produits = grosProduit::where('produitType_id', 1)->get();

        $date = Carbon::now();

        return view('Stocks.recherchePoissonnerie', compact('stocks', 'dateDebut', 'dateFin', 'date', 'produits'));
    }

    /** 
     * PDF recherche poissonnerie
    */
    public function generatePoissonneriePDF(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $libelle = $request->libelle;

        $query = Stock::query()
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->where('produitType_id',1);

        if ($libelle) {
            $query->where('libelle', $libelle);
        }

        $stocks = $query
            ->selectRaw('date, libelle, SUM(quantite) as total_quantite, user_id')
            ->groupBy('date', 'libelle', 'user_id')
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();

        $date = now();

        $pdf = Pdf::loadView('Stocks.entreePoissonneriepdf', compact('stocks', 'dateDebut', 'dateFin', 'date'));

        return $pdf->download('stock-poissonnerie.pdf');
    }


    /**
     * EXCEL poissonnerie
     */
    public function exportPoissonnerieExcel(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $libelle = $request->libelle;
        $fileName = 'stock-poissonnerie-' . now()->format('Y-m-d-H-i') . '.xlsx';

        return Excel::download(new StockPoissonnerieExport($dateDebut, $dateFin, $libelle), $fileName);
    }
    
    /**
     * Sortie divers
     */
     public function sortie()
    {
        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
        ->where('produitType_id', 2)
        ->groupBy('date', 'produit')
        ->orderBy('date', 'asc')
        ->get();

        $produits = grosProduit::where('produitType_id', 2)->get();
       
        return view('Stocks.sortie', compact('factures','produits'));
    }

    /**
     * Rechercher sortie de stock divers
     */
    public function recherche(Request $request)
    {
        // Initialiser les dates de début et de fin
        $dateDebut = $request->filled('dateDebut') ? Carbon::parse($request->dateDebut)->startOfDay() : now()->startOfDay();
        $dateFin = $request->filled('dateFin') ? Carbon::parse($request->dateFin)->endOfDay() : now()->endOfDay();

        if ($dateDebut > $dateFin) {
            return back()->with('error_message', 'La date de début ne peut pas être supérieure à la date de fin.');
        }

        // Requête avec filtres appliqués (date et produit)
        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
            ->where('produitType_id', 2)
            ->when($request->filled('dateDebut'), function ($query) use ($dateDebut) {
                $query->where('date', '>=', $dateDebut);
            })
            ->when($request->filled('dateFin'), function ($query) use ($dateFin) {
                $query->where('date', '<=', $dateFin);
            })
            ->when($request->filled('produit'), function ($query) use ($request) {
                $query->where('produit', $request->produit);
            })
            ->groupBy('date', 'produit')
            ->orderBy('date', 'asc')
            ->get();

        $date = Carbon::now();
        $produits = grosProduit::where('produitType_id', 2)->get();

        return view('Stocks.recherche', compact('factures', 'dateDebut', 'dateFin', 'date','produits'));
    }

    /**
     * pdf sortie divers
     */
     public function exportPDF(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $produit = $request->produit;

        $query = Facture::whereBetween('date', [$dateDebut, $dateFin])
                ->where('produitType_id', 2);



        if ($produit) {
            $query->where('produit', $produit);
        }

        $factures = $query
            ->selectRaw('date, produit, SUM(quantite) as total_quantite')
            ->groupBy('date', 'produit')
            ->orderBy('date', 'desc')
            ->get();

        $pdf = PDF::loadView('Stocks.sortiedivers_pdf', [
            'factures' => $factures,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Sortie-stock-' . now()->format('d-m-Y') . '.pdf');
    }

    /**Excel sortie divers */
    public function exportSortieExcel(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $produit = $request->produit;

       $query = Facture::whereBetween('date', [$dateDebut, $dateFin])
                ->where('produitType_id', 2);



        if ($produit) {
            $query->where('produit', $produit);
        }

        $factures = $query
            ->selectRaw('date, produit, SUM(quantite) as total_quantite')
            ->groupBy('date', 'produit')
            ->orderBy('date', 'desc')
            ->get();

        $export = new SortieStockExport($factures, $dateDebut, $dateFin);

        return Excel::download($export, 'Sortie-stock-' . now()->format('d-m-Y') . '.xlsx');
    }

    /**
     * Sortie poissonnerie
     */
    public function sortiePoissonnerie()
    {
       
        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
        ->where('produitType_id', 1)
        ->groupBy('date', 'produit')
        ->orderBy('date', 'asc')
        ->get();

        $produits = grosProduit::where('produitType_id', 1)->get();

       
        return view('Stocks.sortiePoissonnerie', compact('factures','produits'));
    }
    
    /**
     * Rechercher sortie de stock poissonnerie
     */
    public function recherchePoisson(Request $request)
    {
        // Initialiser les dates de début et de fin
        $dateDebut = $request->filled('dateDebut') ? Carbon::parse($request->dateDebut)->startOfDay() : now()->startOfDay();
        $dateFin = $request->filled('dateFin') ? Carbon::parse($request->dateFin)->endOfDay() : now()->endOfDay();

        if ($dateDebut > $dateFin) {
            return back()->with('error_message', 'La date de début ne peut pas être supérieure à la date de fin.');
        }

        // Requête avec filtres appliqués (date et produit)
        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
            ->where('produitType_id', 1)
            ->when($request->filled('dateDebut'), function ($query) use ($dateDebut) {
                $query->where('date', '>=', $dateDebut);
            })
            ->when($request->filled('dateFin'), function ($query) use ($dateFin) {
                $query->where('date', '<=', $dateFin);
            })
            ->when($request->filled('produit'), function ($query) use ($request) {
                $query->where('produit', $request->produit);
            })
            ->groupBy('date', 'produit')
            ->orderBy('date', 'asc')
            ->get();

        $date = Carbon::now();
        $produits = grosProduit::where('produitType_id', 1)->get();


        return view('Stocks.recherchePoisson', compact('factures', 'dateDebut', 'dateFin', 'date','produits'));
    }

    /**
     * pdf sortie poissonnerie
     */
     public function exportPoissonneriePDF(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $produit = $request->produit;

        $query = Facture::whereBetween('date', [$dateDebut, $dateFin])
                ->where('produitType_id', 1);


        if ($produit) {
            $query->where('produit', $produit);
        }

        $factures = $query
            ->selectRaw('date, produit, SUM(quantite) as total_quantite')
            ->groupBy('date', 'produit')
            ->orderBy('date', 'desc')
            ->get();

        $pdf = PDF::loadView('Stocks.sortiepoissonnerie_pdf', [
            'factures' => $factures,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Sortie-stock-poissonnerie-' . now()->format('d-m-Y') . '.pdf');
    }

    /**Excel sortie poissonnerie */
    public function exportSortiePoissonnerieExcel(Request $request)
    {
        $dateDebut = $request->dateDebut ?? now()->startOfMonth()->toDateString();
        $dateFin = $request->dateFin ?? now()->toDateString();
        $produit = $request->produit;

       $query = Facture::whereBetween('date', [$dateDebut, $dateFin])
                ->where('produitType_id', 1);


        if ($produit) {
            $query->where('produit', $produit);
        }

        $factures = $query
            ->selectRaw('date, produit, SUM(quantite) as total_quantite')
            ->groupBy('date', 'produit')
            ->orderBy('date', 'desc')
            ->get();

        $export = new SortieStockPoissonnerieExport($factures, $dateDebut, $dateFin);

        return Excel::download($export, 'Sortie-stock-poissonnerie-' . now()->format('d-m-Y') . '.xlsx');
    }

    /**
     * Actuel stock detail divers
     */
    public function actuel()
    {
        // Récupérer tous les produits avec leurs quantités et les sorties correspondantes
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.produitType_id', 2)
            ->groupBy('gros_produits.id')
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        return view('Stocks.actuel', compact('produits'));
    }

    /**
     * PDF du stock actuel divers
     */
    public function exportActuelDiversPDF()
    {
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.produitType_id', 2)
            ->groupBy('gros_produits.id') // Assurez-vous que la clé primaire `id` existe dans gros_produits
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        $date = now()->format('d/m/Y');

        $pdf = Pdf::loadView('Stocks.actuel_divers_pdf', compact('produits','date'));
        return $pdf->download('Stocks_actuel_divers_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Actuel stock de la poissonerie
     */
    public function actuelPoissonerie()
    {
        // Récupérer tous les produits avec leurs quantités et les sorties correspondantes
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.produitType_id', 1)
            ->groupBy('gros_produits.id') // Assurez-vous que la clé primaire `id` existe dans gros_produits
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        return view('Stocks.actuelPoissonerie', compact('produits'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock_actuel' => 'required|numeric|min:0',
        ]);

        $produit = grosProduit::findOrFail($id);

        // Pour mettre à jour le stock actuel
        $totalSortie = DB::table('factures')
            ->where('produit', $produit->libelle)
            ->sum('quantite');

        // Nouvelle quantité totale = stock_actuel + sorties
        $produit->quantite = $request->stock_actuel + $totalSortie;
        $produit->save();

        return redirect()->back()->with('success_message', 'Stock mis à jour avec succès.');
    }



    /**
     * PDF du stock actuel poissonnerie
     */
    public function exportActuelPoissonneriePDF()
    {
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.produitType_id', 1)
            ->groupBy('gros_produits.id') // Assurez-vous que la clé primaire `id` existe dans gros_produits
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        $date = now()->format('d/m/Y');

        $pdf = Pdf::loadView('Stocks.actuel_poissonnerie_pdf', compact('produits','date'));
        return $pdf->download('Stocks_actuel_poissonnerie_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * inventair detail divers
     */
    public function indexinventaire()
    {
        $today = Carbon::now();

        //Affiche tout les gros de la table grosProduit
        $produits = grosProduit::where('produitType_id', '=', 2)->get();

        // Remplacer par ceci
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();

        // Creez un tableau associatif pour stocker la quantite de sortie par produit
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
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Inventaires.index', compact('produits','today'));
    }

    /**
     * inventair poissonnerie
     */
    public function indexinventairePoissonnerie()
    {
        $today = Carbon::now();

        //Affiche tout les gros de la table grosProduit
        $produits = grosProduit::where('produitType_id', '=', 1)->get();

        // Remplacer par ceci
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();

        // Creez un tableau associatif pour stocker la quantite de sortie par produit
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
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Inventaires.indexPoissonnerie', compact('produits','today'));
    }
    /**
     * enregistrer stock en attente
     */
     public function storeAttente(Request $request)
    {
        $stock= new stockAttente();
    
        $stock->libelle = $request->produit;        
        $stock->quantite = $request->quantite;
        $stock->date = Carbon::now();
        $stock->produitType_id = 2;
        $stock->user_id = Auth::user()->id;
        $stock->save();

        $users = User::whereIN('role_id', [1,3])->get(); // ✅ bon filtrage multiple

        foreach ($users as $user) {
            $user->notify(new StockNotification($stock));
        }

        return redirect()->route('stockAttente.index')->with('success_message', 'Stock entrés avec succès.');
    }

    /**
     * enregistrer stock en attente poissonnerie
     */
     public function storeAttentePoissonnerie(Request $request)
    {
        $stock= new stockAttente();
        $stock->libelle = $request->produit;        
        $stock->quantite = $request->quantite;
        $stock->date = Carbon::now();
        $stock->produitType_id = 1;
        $stock->user_id = Auth::user()->id;
        $stock->save();

        $users = User::whereIN('role_id', [1,3])->get(); // ✅ bon filtrage multiple

        foreach ($users as $user) {
            $user->notify(new StockNotification($stock));
        }

        return redirect()->route('stockAttente.index')->with('success_message', 'Stock entrés avec succès.');
    }

    /**
     * Liste des stock en attente de validation
     */
    public function listeStockAttente(Request $request)
    {
        $stocks= stockAttente::all();

        return view('Stocks.attenteListe', compact('stocks'));
    }

    public function valider($id)
    {
        // Récupérer l'entrée stock en attente
        $stockAttente = stockAttente::findOrFail($id);
        // Créer un nouveau stock avec les infos récupérées
        $stock = new Stock();
        $stock->libelle = $stockAttente->libelle;
        $stock->quantite = $stockAttente->quantite;
        $stock->date = $stockAttente->date ?? now(); // fallback à aujourd'hui si null
        $stock->produitType_id = $stockAttente->produitType_id;
        $stock->user_id = auth()->id(); // utilisateur qui valide
        $stock->save();

        $produit = grosProduit::where('libelle', $stockAttente->libelle)
        ->where('produitType_id', $stockAttente->produitType_id)
        ->first();     

        // Mettez à jour la quantité du produit
        $nouvelleQuantite = $produit->quantite + $stockAttente->quantite;
        $produit->update(['quantite' => $nouvelleQuantite, 'prix'=>$produit->prix]);
        $produit->update(['prix'=>$produit->prix]);

        // Supprimer l'entrée de la table stockAttente
        $stockAttente->delete();

        $users = User::where('role_id', 2)->get(); // on récupère TOUS les utilisateurs avec ce rôle

        foreach ($users as $user) {
            $user->notify(new ValiderNotification($stock)); // notification à chacun
        }

        // Redirection avec message de succès
        return redirect()->route('stockAttente.index')->with('success_message', 'Stock validé avec succès et déplacé.');
    }

    /**
     * Enregistrer une entrées de stock divers
     */
    // public function store(Request $request)
    // {
    //     $stock = new Stock();
    //     // Récupérer les données JSON envoyées depuis le formulaire
    //     $stock->libelle = $request->produit;        
    //     $stock->quantite = $request->quantite;
    //     $stock->date = Carbon::now();
    //     $stock->produitType_id = 2;
    //     $stock->user_id = Auth::user()->id;
    //     $stock->save();

    //     return redirect()->route('stock.entrer')->with('success_message', 'Stock entrés avec succès.');
    // }

     /**
     * Enregistrer une entrées de stock poissonnerie
     */
    // public function storePoissonnerie(Request $request)
    // {
    //     $stock = new Stock();
    //     // Récupérer les données JSON envoyées depuis le formulaire
    //     $stock->libelle = $request->produit;
    //     $stock->quantite = $request->quantite;
    //     $stock->date = Carbon::now();
    //     $stock->produitType_id = 1;
    //     $stock->user_id = Auth::user()->id;
    //     $stock->save();

    //     return redirect()->route('stock.entrerPoissonerie')->with('success_message', 'Stock entrés avec succès.');
    // }

    /**
     * Annuler entrer de stock divers
     */
    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'libelle' => 'required',
            'quantite' => 'required|numeric|min:0',
        ]);

        // Récupérer l'ancien stock
        $stock = Stock::findOrFail($id);
        $ancienStock = $stock->quantite;

        // Récupérer le produit et son total de sorties
        $produit = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.id',
                'gros_produits.libelle',
                'gros_produits.quantite',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.libelle', $request->libelle)
            ->where('gros_produits.produitType_id', 2)
            ->groupBy('gros_produits.id', 'gros_produits.libelle', 'gros_produits.quantite') // obligatoire avec MySQL
            ->first();


        if (!$produit) {
            return back()->with('error_message', 'Produit introuvable.');
        }

        // Calcul du stock actuel
        $stock_actuel = $produit->quantite - $produit->total_sortie;

        // Vérifier si le stock permet la suppression
        if ($stock_actuel < $ancienStock) {
            return back()->with('error_message', 'Impossible de supprimer ce stock car la quantité actuelle du produit est insuffisante.');
        }

        // Mise à jour du produit
        $nouvelleQuantite = $produit->quantite - $ancienStock;
        grosProduit::where('id', $produit->id)->update(['quantite' => $nouvelleQuantite]);

        // Suppression du stock
        $stock->delete();

        return redirect()->route('stock.entrer')
            ->with('success_message', 'Stock supprimé avec succès.');
    }

    /**
     * Annuler entrer de stock poissonnerie
     */
    public function updatePoissonnerie(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'libelle' => 'required',
            'quantite' => 'required|numeric|min:0',
        ]);

        // Récupérer l'ancien stock
        $stock = Stock::findOrFail($id);
        $ancienStock = $stock->quantite;

        // Récupérer le produit et son total de sorties
        $produit = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.id',
                'gros_produits.libelle',
                'gros_produits.quantite',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->where('gros_produits.libelle', $request->libelle)
            ->where('gros_produits.produitType_id', 1)
            ->groupBy('gros_produits.id', 'gros_produits.libelle', 'gros_produits.quantite') // obligatoire avec MySQL
            ->first();

        if (!$produit) {
            return back()->with('error_message', 'Produit introuvable.');
        }

        // Calcul du stock actuel
        $stock_actuel = $produit->quantite - $produit->total_sortie;

        // Vérifier si le stock permet la suppression
        if ($stock_actuel < $ancienStock) {
            return back()->with('error_message', 'Impossible de supprimer ce stock car la quantité actuelle du produit est insuffisante.');
        }

        // Mise à jour du produit
        $nouvelleQuantite = $produit->quantite - $ancienStock;
        grosProduit::where('id', $produit->id)->update(['quantite' => $nouvelleQuantite]);
        // Suppression du stock
        $stock->delete();

        return redirect()->route('stock.entrerPoissonerie')->with('success_message', 'Stock supprimé avec succès.');
    }

    /**
     * Supprimer stock en attente
     */
    public function delete(stockAttente $stock)
    {
        try {
            $stock->delete();
            return back()->with('success_message', 'Stock supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

}
