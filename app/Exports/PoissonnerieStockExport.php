<?php 

namespace App\Exports;

use App\Models\grosProduit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PoissonnerieStockExport implements FromView
{
    public function view(): View
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

        return view('stocks.actuel_poissonnerie_excel', compact('produits','date'));
    }
}
