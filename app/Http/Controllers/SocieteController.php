<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Exception;
use Illuminate\Http\Request;

class SocieteController extends Controller
{
    
    /**
     * Liste des societe
     */
    public function index()
    {
        $societes = Societe::paginate(10);

        return view('Societes.index',compact('societes'));
    }


    /**
     * Enregistrer une societe
     */
    public function store(Societe $societe, Request $request)
    {
        try {
            $societe->societe = $request->societe;
        
            $societe->save();

            return back()->with('success_message', 'Société enregistré avec succès');

        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    /**
     * Supprimer societe
     */
    public function delete(Societe $societe)
    {
        try {
            $societe->delete();
            return back()->with('success_message', 'Société supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

    
    /**
     * Editer une societe
     */
    public function update(Societe $societe, Request $request)
    {
        try {
            $societe->societe = $request->societe;

            $societe->update();

            return back()->with('success_message', 'Société mis à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

}
