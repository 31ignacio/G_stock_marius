<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(AuthRequest $request)
    {
        // Récupérer les identifiants envoyés
        $credentials = $request->only(['email', 'password']);
    
        // Vérification des identifiants
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Obtenir l'utilisateur connecté
    
            // Vérifier si le compte est actif
            if ($user->estActif == 0) {
                return redirect()->route('accueil.index'); // Rediriger vers l'accueil si actif
            } else {
                Auth::logout(); // Déconnecter l'utilisateur
                return redirect()->back()->with('error_msg', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
            }
        } else {
            return redirect()->back()->with('error_msg', 'Paramètres de connexion non reconnus');
        }
    }
    
    

  
}
