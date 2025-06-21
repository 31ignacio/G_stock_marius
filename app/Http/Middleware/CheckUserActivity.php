<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->last_activity = now();
            $user->update();
            //dd($user);

            // Vérifie si le temps d'inactivité est supérieur ou égal à 1 minute
            if ($user->last_activity && now()->diffInMinutes($user->last_activity) >= 1) {
                // Déconnecte l'utilisateur seulement s'il est encore actif
                if (auth()->check()) {
                    auth()->logout();
                    return redirect('/')->with('error_msg', 'Vous avez été déconnecté en raison d\'une inactivité.');
                }
            }

            // Met à jour le temps de la dernière activité de l'utilisateur
            $user->last_activity = now();
            $user->save();
        }

        return $next($request);
    }
}
