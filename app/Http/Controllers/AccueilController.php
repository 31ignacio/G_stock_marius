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
        return $factur->code . $factur->date . $factur->totalTTC . $factur->montantPaye . $factur->mode;
    })->sortByDesc('date');

    // Éliminer les doublons de factures
    $codesFacturesUniques = $facture->unique(function ($factur) {
        return $factur->code . $factur->date . $factur->totalTTC . $factur->montantPaye . $factur->mode . $factur->produitType_id;
    })->sortByDesc('date');


    // Filtrer les factures par date d'aujourd'hui
    $facturesAujourdhuiSuper = $codesFacturesUniques->filter(function ($facture) {
        return Carbon::parse($facture->date)->isToday() && $facture->produitType_id == 2;
    });

    $facturesAujourdhuiPoissonnerie = $codesFacturesUniques->filter(function ($facture) {
        return Carbon::parse($facture->date)->isToday() && $facture->produitType_id == 1;
    });

    $facturesAujourdhui = $codesFacturesUniquesTout->filter(function ($facture) {
        return Carbon::parse($facture->date)->isToday();
    });

    $sommeMontant = $facturesAujourdhuiSuper->sum('total');
    $sommeMontantPoissonnerie = $facturesAujourdhuiPoissonnerie->sum('total');

    // Puis tu calcules la somme du champ "montantFinal" ainsi :
    $totalMontantFinal = $codesFacturesUniques->sum('montantFinal');

    return view('Accueil.index', compact(
        'role',
        'nombreClient',
        'sommeMontant',
        'sommeMontantPoissonnerie',
        'facturesAujourdhui','totalMontantFinal'
    ));

}
}

