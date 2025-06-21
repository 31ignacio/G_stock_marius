<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUsersRequest;
use App\Models\Facture;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;

class AdminController extends Controller
{
   
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(){
      
        $admins = User::orderBy('created_at', 'desc')->paginate(6);

        $roles = Role::all();
      
        return view('Admin.index',compact('admins','roles'));
    }

    /**
     * Enregistrer un utilsateur
    */
    public function store(User $user,createUsersRequest $request)
    {

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            $user->role_id= $request->role;
            $user->password =Hash::make($request->password);
            $user->save();

            return redirect()->route('admin')->with('success_message', 'Utilisateur ajouté avec succès');
            
        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

   
    public function logout(){

        FacadesSession::flush();
        Auth::logout();

        return redirect()->route('login');
    }


    public function update(User $admin, Request $request)
    {
        //Enregistrer un nouveau département
        try {
            $admin->name = $request->nom;
            $admin->email = $request->email;
            $admin->telephone = $request->telephone;
            $admin->role_id = $request->role;

            $admin->update();

            return redirect()->route('admin')->with('success_message', 'Utilisateur mis à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }


    public function delete(User $admin)
    {
        try {
            $admin->delete();

            return redirect()->route('admin')->with('success_message', 'Utilisateur supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

    public function toggleStatus(User $admin)
    {
        $admin->estActif = !$admin->estActif; // Bascule entre 0 et 1
        $admin->save();

        return redirect()->back()->with('success_message', 'Statut mis à jour avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
    */
    public function detail($admin)
    {

        $factures = Facture::where('user_id', $admin)->get();
        $admins = User::where('id', $admin)->first();

        // Créez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->totalTTC . $facture->montantPaye;
        });

        $dateAujourdhui = Carbon::now();

        // Date d'hier
        $dateHier = $dateAujourdhui->copy()->subDay();

        // Filtrer les factures par date d'aujourd'hui
        $facturesAujourdhui = $codesFacturesUniques->where('date', $dateAujourdhui);

        // Calculer la somme des montants finaux pour les factures d'aujourd'hui
        $sommeMontantFinalAujourdhui = $facturesAujourdhui->sum('montantFinal');

       // Date du mois actuel
        $dateMoisActuel = Carbon::now()->startOfMonth();

        // Filtrer les factures pour le mois actuel
        $facturesMoisActuel = $codesFacturesUniques->filter(function ($facture) use ($dateMoisActuel) {
            return Carbon::parse($facture->date)->startOfMonth()->equalTo($dateMoisActuel);
        });

        // Calcul de la somme montantFinal pour le mois actuel
        $sommeMontantFinalMoisActuel = $facturesMoisActuel->sum('montantFinal');
        $sommeMontantFinalTousMois = $codesFacturesUniques->sum('montantFinal');

        $salaire= $admins->salaire;
    
        return view('Admin.detail', compact('codesFacturesUniques', 'admin','admins','sommeMontantFinalAujourdhui','sommeMontantFinalMoisActuel','sommeMontantFinalTousMois' ));
    }


}
