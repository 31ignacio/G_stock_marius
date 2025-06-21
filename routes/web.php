<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FactureAchatController;
use App\Http\Controllers\SocieteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'handleLogin'])->name('handleLogin');

Route::prefix('admin')->group(function () {
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/index', [AdminController::class, 'index'])->name('admin');
    Route::post('/create', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/update/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('delete/{admin}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::PATCH('/toggleStatus/{admin}', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');
});

 Route::middleware(['auth'])->group(function(){

    Route::get('/accueil', [AccueilController::class, 'index'])->name('accueil.index');

    Route::prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('client.index');
        Route::get('/detail{client}}', [ClientController::class, 'detail'])->name('client.detail');
        Route::post('/create', [ClientController::class, 'store'])->name('client.store');
        Route::put('/update/{client}', [ClientController::class, 'update'])->name('client.update');    
        Route::delete('Client/{client}', [ClientController::class, 'delete'])->name('client.delete');
    });
    
    Route::prefix('produit')->group(function () {
        Route::get('/', [ProduitController::class, 'index'])->name('produit.index');
        // Route::get('/produitsGros', [ProduitController::class, 'index2'])->name('produit.index2');
        Route::post('/create', [ProduitController::class, 'store'])->name('produit.store');
        Route::put('/produit/{produit}/update', [ProduitController::class, 'update'])->name('produit.update');
        Route::delete('/{produit}', [ProduitController::class, 'delete'])->name('produit.delete');
    });


    Route::prefix('societe')->group(function () {
        Route::get('/', [SocieteController::class, 'index'])->name('societe.index');
        Route::post('/create', [SocieteController::class, 'store'])->name('societe.store');
        Route::put('/update/{societe}', [SocieteController::class, 'update'])->name('societe.update');
        Route::delete('/{societe}', [SocieteController::class, 'delete'])->name('societe.delete');
    });

    
    
    Route::prefix('facture')->group(function () {
        Route::get('/', [FactureController::class, 'index'])->name('facture.index');
        Route::get('/create', [FactureController::class, 'create'])->name('facture.create');
        Route::post('/create', [FactureController::class, 'store'])->name('facture.store');
        Route::get('/details/{code}/{date}',[FactureController::class, 'details'])->name('facture.details');
        Route::get('/annuler',[FactureController::class, 'annuler'])->name('facture.annuler');
        Route::get('/pointJournée', [FactureController::class, 'point'])->name('facture.point');

        //  Route::get('/pdf/{facture}', [FactureController::class, "pdf"])->name('facture.telecharger');
        Route::get('/facture/telecharger/{code}/{date}', [FactureController::class, 'pdf'])->name('facture.telecharger');
        Route::get('/{facture}', [FactureController::class, 'delete'])->name('facture.delete');
        Route::get('/recherche/search', [FactureController::class, 'recherche'])->name('facture.search');
        Route::get('/facture/impression/{code}/{date}', [FactureController::class, 'impression'])->name('facture.impression');
        //genere pdf sommation facture
        Route::get('/facture/genererPDF', [FactureController::class, 'genererPDF'])->name('facture.genererPDF');
        Route::get('/facture/genererExcel', [FactureController::class, 'genererExcel'])->name('facture.genererExcel');

    });

    Route::prefix('factureAchat')->group(function () {
        Route::get('/', [FactureAchatController::class, 'index'])->name('factureAchat.index');
        Route::get('/Achat/create', [FactureAchatController::class, 'create'])->name('factureAchat.create');
        Route::post('/Achat/store', [FactureAchatController::class, 'store'])->name('factureAchat.store');
        Route::get('/details/{code}/{date}',[FactureAchatController::class, 'details'])->name('factureAchat.details');
        Route::get('/annuler',[FactureAchatController::class, 'annuler'])->name('factureAchat.annuler');
        Route::get('/pointJournée', [FactureAchatController::class, 'point'])->name('factureAchat.point');    
        Route::get('/{facture}', [FactureAchatController::class, 'delete'])->name('factureAchat.delete');
        Route::get('/recherche/search', [FactureAchatController::class, 'recherche'])->name('factureAchat.search');
        Route::get('/facture/impression/{code}/{date}', [FactureAchatController::class, 'impression'])->name('factureAchat.impression');
        Route::get('/facture/telecharger/{code}/{date}', [FactureAchatController::class, 'pdf'])->name('factureAchat.telecharger');

        Route::get('/download/{facture}', [FactureAchatController::class, 'download'])->name('facture.download');

    });
    
    
    Route::prefix('stock')->group(function () {
        Route::get('/index', [StockController::class, 'index'])->name('stock.index');
        Route::get('/entrer', [StockController::class, 'entrer'])->name('stock.entrer');
        Route::get('/entrer/poissonnerie', [StockController::class, 'entrerPoissonnerie'])->name('stock.entrerPoissonerie');
        Route::get('/sortie', [StockController::class, 'sortie'])->name('stock.sortie');
        Route::get('stock/actuel', [StockController::class, 'actuel'])->name('stock.actuel');
        Route::get('stock/actuelPoissonerie', [StockController::class, 'actuelPoissonerie'])->name('stock.actuelPoissonerie');
        Route::post('/create', [StockController::class, 'store'])->name('stock.store');
        Route::post('/create/Poissonnerie', [StockController::class, 'storePoissonnerie'])->name('stock.storePoissonnerie');
        Route::get('/inventaires/details', [StockController::class, 'indexinventaire'])->name('inventaires.index');
        Route::get('/inventaires/poissonnerie', [StockController::class, 'indexinventairePoissonnerie'])->name('inventaires.indexPoissonnerie');

        Route::get('/recherche/detail', [StockController::class, 'rechercheDetail'])->name('stock.rechercheDetail');
        Route::get('/recherche/poissonnerie', [StockController::class, 'recherchePoissonnerie'])->name('stock.recherchePoissonnerie');
        Route::post('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
        Route::post('/stock/poissonnerie/{id}', [StockController::class, 'updatePoissonnerie'])->name('stock.updatePoissonnerie');

        Route::get('sortie/recherche/search', [StockController::class, 'recherche'])->name('sortieDetail.search');
        
        Route::get('/sortie/poissonnerie', [StockController::class, 'sortiePoissonnerie'])->name('stock.sortiePoissonnerie');
        Route::get('sortie/recherche/poissonnerie', [StockController::class, 'recherchePoisson'])->name('sortiePoissonnerie.search');
        /**DIVERS */
        Route::get('/stock/pdf', [StockController::class, 'generatePDF'])->name('stock.generatePDF');
        Route::get('/stock/excel', [StockController::class, 'exportExcel'])->name('stock.exportExcel');
        Route::get('/sortie-stock/pdf', [StockController::class, 'exportPDF'])->name('sortieDetail.pdf');
        Route::get('/sortie-stock/excel', [StockController::class, 'exportSortieExcel'])->name('sortieDetail.excel');
        /**POISSONNERIE */
        Route::get('/stock/poissonnerie/pdf', [StockController::class, 'generatePoissonneriePDF'])->name('stock.generatePoissonneriePDF');
        Route::get('/stock/poissonnerie/excel', [StockController::class, 'exportPoissonnerieExcel'])->name('stock.exportPoissonnerieExcel');
        Route::get('/sortie-stock/poissonnerie/pdf', [StockController::class, 'exportPoissonneriePDF'])->name('sortieDetailPoissonnerie.pdf');
        Route::get('/sortie-stock/poissonnerie/excel', [StockController::class, 'exportSortiePoissonnerieExcel'])->name('sortieDetailPoissonnerie.excel');

    }); 
 });
