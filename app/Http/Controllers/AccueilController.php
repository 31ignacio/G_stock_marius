<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AccueilController extends Controller
{

   public function index()
{
    $nombreClient = Client::count();
    $facture = Facture::all();
    $role = Auth::user()->role_id;

    //pour tout les factures
    $codesFacturesUniquesTout = $facture->unique(function ($factur) {
        return $factur->code . $factur->date  . $factur->montantFinal ;
    })->sortByDesc('date');

     // Filtrer uniquement les factures du jour
    $facturesAujourdhui = $codesFacturesUniquesTout->filter(function ($facture) {
        return Carbon::parse($facture->date)->isToday();
    });


    // ✅ Ventes Poissonnerie (produitType_id = 1)
    $sommeMontantPoissonnerie = $facturesAujourdhui->where('produitType_id', 1)->sum('montantFinal');

    // ✅ Ventes Divers (produitType_id = 2)
    $sommeMontant = $facturesAujourdhui->where('produitType_id', 2)->sum('montantFinal');

    // ✅ Réductions du jour
    $sommeMontantReduction = $facturesAujourdhui->sum('reduction');

    // Puis tu calcules la somme du champ "montantFinal" ainsi :
    $totalMontantFinal = $codesFacturesUniquesTout->sum('montantFinal');


    return view('Accueil.index', compact(
        'role',
        'nombreClient',
        'sommeMontant',
        'sommeMontantPoissonnerie',
        'facturesAujourdhui','totalMontantFinal','sommeMontantReduction'
    ));

}
}

