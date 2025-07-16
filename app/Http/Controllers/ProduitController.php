<?php

namespace App\Http\Controllers;

use App\Models\ProduitType;
use App\Models\grosProduit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    //Liste de tout les produits
    public function index()
    {
        return view('Produits.index', [
            'produits' => GrosProduit::all(),
            'produitTypes' => ProduitType::all(),
        ]);
    }
    
    //Enregistrer le produit dans la base de donnée
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            // 'prix' => 'required|numeric|min:0',
            'produitType' => 'required|exists:produit_types,id',
            // 'quantite' => 'required|numeric|min:0',
            'dateReception' => 'nullable|date',
        ]);

        // Récupérer le dernier numéro du produit enregistré
     
        $dernierProduit = DB::table('gros_produits')->latest('id')->first();
        $nouveauNumero = $dernierProduit ? $dernierProduit->id + 1 : 1;

        // Générer un code avec 6 chiffres formatés (ex: 000001)
        $code = str_pad($nouveauNumero, 6, '0', STR_PAD_LEFT);
        // Vérifier si le produit existe déjà avec les mêmes attributs clés
        $existingProduct = grosProduit::where([
            ['libelle', $request->libelle],
        ])->exists();
    
        if ($existingProduct) {
            return back()->with('error_message', 'Ce produit existe déjà.');
        }
    
        $productData = [
            'code' => $code,
            'libelle' => $request->libelle,
            'prix' => 0,
            'produitType_id' => $request->produitType,
            'quantite' => 0,
            'dateReception' => $request->dateReception,
        ];
    
        try {
            // Enregistrement du produit
            grosProduit::create($productData);
    
            return redirect()->route('produit.index')
                ->with('success_message', 'Produit enregistré avec succès.');
    
        } catch (Exception $e) {
    
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    
   
    public function update(grosProduit $produit, Request $request){

        $exists = grosProduit::where('id', '!=', $produit->id)
            ->where('libelle', $request->libelle)
            ->exists();

        if ($exists) {
            return back()->with('error_message', 'Un autre produit avec les mêmes informations existe déjà.');
        }

        try {

            $produit->update([
                'libelle' => $request->libelle,
                'prix' => 0,
                'produitType_id' => $request->produitType,
                'dateExpiration' => $request->dateExpiration,
                'dateReception' => $request->dateReception,
            ]);

            return redirect()->route('produit.index')->with('success_message', 'Produit modifié avec succès.');
        } catch (\Exception $e) {
           
            return back()->with('error_message', 'Une erreur est survenue pendant la modification.');
        }
    }
    
    /**
     * Supprimer un produit
     */
    public function delete(grosProduit $produit)
    {
        try {
            $produit->delete();
            return back()->with('success_message', 'Produit supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

}
