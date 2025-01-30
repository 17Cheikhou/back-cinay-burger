<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class StatisticController extends Controller
{
    public function commandesEnCours(): JsonResponse
    {
        $aujourdhui = now()->startOfDay();
        $commandesEnCours = Commande::where('statut', 'en_attente')
            ->whereDate('updated_at', $aujourdhui)
            ->count();

        return response()->json(['commandes_en_cours' => $commandesEnCours]);
    }

    public function commandesValidees(): JsonResponse
    {
        $aujourdhui = now()->startOfDay();
        $commandesValidees = Commande::where('statut', 'valider')
            ->whereDate('updated_at', $aujourdhui)
            ->count();

        return response()->json(['commandes_validees' => $commandesValidees]);
    }

    public function recettesJournalieres(): JsonResponse
    {
        $aujourdhui = now()->startOfDay();
        $recettesJournalieres = Commande::where('statut', 'payer')
            ->whereDate('updated_at', $aujourdhui)
            ->sum('montant_total');


        return response()->json(['recettes_journalieres' => $recettesJournalieres]);
        Log::info('recette : ' .$recettesJournalieres );

    }

    public function commandesAnnulees(): JsonResponse
    {
        $aujourdhui = now()->startOfDay();
        $commandesAnnulees = Commande::where('statut', 'annuler')
            ->whereDate('updated_at', $aujourdhui)
            ->count();

        return response()->json(['commandes_annulees' => $commandesAnnulees]);
    }
}
