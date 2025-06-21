<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveClientRequest;
use Exception;
use App\Models\Client;
use App\Models\Facture;


class ClientController extends Controller
{
    //
    /**
     * Liste des clients
     */
    public function index()
    {
        $clients = Client::all();

        return view('Clients.index', compact('clients'));
    }

    /**
     * Voir les details sur un client
     */
    public function detail($client)
    {

        $factures = Facture::where('client', $client)->get();

        // Créez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->totalTTC . $facture->montantPaye . $facture->mode;
        });
        $totalTTCClient = $codesFacturesUniques->sum('montantFinal');


        return view('Clients.detail', compact('codesFacturesUniques', 'client','totalTTCClient'));
    }

    /**
     * Enregistrer un client
     */
    public function store(Client $client, saveClientRequest $request)
    {
       
        try {
            $client->nom = $request->nom;
            $client->raisonSociale = $request->societe;
            $client->telephone = $request->telephone;
            $client->ville = $request->ville;

            $client->save();

            return back()->with('success_message', 'Client enregistré avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    /**
     * Editer un client
     */
    public function update(Client $client, saveClientRequest $request)
    {
        
        try {
            $client->nom = $request->nom;
            $client->raisonSociale = $request->societe;
            $client->telephone = $request->telephone;
            $client->ville = $request->ville;
            $client->update();

            return back()->with('success_message', 'Client mis à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    /**
     * Supprimer un client
     */
    public function delete(Client $client)
    {
        try {
            $client->delete();
            return back()->with('success_message', 'Client supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

}
