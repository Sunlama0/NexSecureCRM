<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord.
     */
    public function index()
    {
        // Récupérer les données des matériels
        $company_id = auth()->user()->company_id;

        // Matériels disponibles (en stock)
        $stocksDisponibles = Material::where('status', 'En stock')
                                    ->where('company_id', $company_id)
                                    ->count();

        // Matériels attribués
        $materielsAttribues = Material::where('status', 'Attribué')
                                    ->where('company_id', $company_id)
                                    ->count();

        // Matériels sortis
        $materielsSortis = Material::where('status', 'Sorti')
                                    ->where('company_id', $company_id)
                                    ->count();

        // Graphique : Coût des matériels attribués par mois
        $costsData = Material::selectRaw('MONTH(assigned_date) as month, SUM(cost) as total_cost')
                            ->where('status', 'Attribué')
                            ->whereYear('assigned_date', date('Y'))
                            ->where('company_id', $company_id)
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        // Graphique : Nombre de PC attribués par mois
        $pcData = Material::selectRaw('MONTH(assigned_date) as month, COUNT(*) as total_pc')
                            ->where('status', 'Attribué')
                            ->whereYear('assigned_date', date('Y'))
                            ->where('company_id', $company_id)
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        // Transformer les données pour les graphiques
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $costs = [];
        $pcAssigned = [];

        for ($i = 1; $i <= 12; $i++) {
            $costForMonth = $costsData->firstWhere('month', $i);
            $pcForMonth = $pcData->firstWhere('month', $i);

            $costs[] = $costForMonth ? $costForMonth->total_cost : 0;
            $pcAssigned[] = $pcForMonth ? $pcForMonth->total_pc : 0;
        }

        // Passer les données à la vue
        return view('dashboard.index', compact(
            'stocksDisponibles',
            'materielsAttribues',
            'materielsSortis',
            'months',
            'costs',
            'pcAssigned'
        ));
    }
}
