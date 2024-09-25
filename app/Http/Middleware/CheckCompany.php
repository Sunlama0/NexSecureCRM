<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est bien connecté
        if (!auth()->check()) {
            // Rediriger vers la page de connexion si non connecté
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a bien un company_id valide
        if (auth()->user()->company_id === null) {
            // Rediriger vers une page d'erreur si l'utilisateur n'a pas de company_id
            return redirect()->route('no-company');
        }

        return $next($request);
    }
}
