<?php

namespace App\Http\Controllers;

use App\Mail\CommandeValider;
use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{

    public function generatePdf($commandeId)
    {
        $commande = Commande::findOrFail($commandeId);
        $pdf = Pdf::loadView('pdf.commande', ['commande' => $commande]);

        $pdf->save(storage_path('app/public/commande.pdf'));

        return $pdf->download('commande.pdf');
    }

    /**
     * Afficher une liste de commandes.
     */
    public function index()
    {
        $commandes = Commande::all();
        return response()->json($commandes ,200);

    }

    /**
     * Stocker une nouvelle commande.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone_client' => 'required|string|max:20',
            'statut' => 'required|in:en_attente,termine,annule,paye',
            'montant_total' => 'nullable|numeric',
            'date_paiement' => 'nullable|date',
            'montant_paiement' => 'nullable|numeric',
            'burger_id' => 'required|exists:burgers,id',
            'quantite' => 'required|integer|min:1',
        ]);
        $existingCommande = Commande::where('burger_id', $request->burger_id)
            ->where('telephone_client', $request->telephone_client)
            ->first();

        if ($existingCommande) {
            return response()->json(['error' => 'Vous avez déjà commandé ce burger.'], 422);
        }
        $datePaiement = $request->date_paiement ?? now();

        $commande = Commande::create([
            'nom_client' => $request->nom_client,
            'prenom_client' => $request->prenom_client,
            'telephone_client' => $request->telephone_client,
            'statut' => $request->statut,
            'montant_total' => $request->montant_total,
            'date_paiement' => $datePaiement,
            'montant_paiement' => $request->montant_paiement,
            'burger_id' => $request->burger_id,
            'quantite' => $request->quantite,
            'email' => $request->email,
        ]);
        Log::info('Commande créée : ', $commande->toArray());

        return response()->json($commande, Response::HTTP_CREATED);
    }

    /**
     * Afficher une commande spécifique.
     */
    public function show($id)
    {

        $commande = Commande::with('burger')->findOrFail($id);

        return response()->json($commande);
    }
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        $commandes = Commande::where(function ($query) use ($searchTerm) {
            $query->where('nom_client', 'like', '%' . $searchTerm . '%')
                ->orWhere('prenom_client', 'like', '%' . $searchTerm . '%')
                ->orWhere('telephone_client', 'like', '%' . $searchTerm . '%')
                ->orWhere('statut', 'like', '%' . $searchTerm . '%')
                ->orWhereDate('created_at', $searchTerm);
        })->get();

        return response()->json($commandes, 200);
    }

    /**
     * Mettre à jour une commande spécifique.
     */

    public function update(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,terminer,annuler,payer,valider',
        ]);

        Log::info('Statut reçu : ' . $request->statut);


        $commande->update([
            'statut' => $request->statut,
        ]);

        if ($request->statut === 'valider') {
            Log::info('Email du client: ' . $commande->email);
            if (isset($commande->email) && !empty($commande->email)) {
                try {
                    Mail::to($commande->email)->send(new CommandeValider($commande));
                } catch (\Exception $e) {
                    // Gestion des erreurs
                    Log::error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
                }
            } else {
                Log::error('Adresse e-mail non valide pour la commande ID: ' . $commande->id);
            }
        }
        return response()->json($commande, 200);
    }


    /**
     * Supprimer une commande spécifique.
     */
    public function destroy(Commande $commande)
    {
        $commande->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
