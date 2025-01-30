<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthContoller extends Controller
{
    /**
     * Inscription des gestionnaires.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        Log::info('Données validées : ', $validated);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Log::info('Utilisateur créé : ', $user->toArray());

        // Connexion automatique après l'inscription
        Auth::login($user);

        return response()->json(['message' => 'Inscription réussie', 'user' => $user]);
    }

    /**
     * Connexion des gestionnaires.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentification réussie
            return response()->json(['message' => 'Connexion réussie', 'user' => $credentials]);
        }
        Log::info('Utilisateur créé : ', $credentials->toArray());

        // Authentification échouée
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    /**
     * Déconnexion des gestionnaires.
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Récupération des informations de l'utilisateur connecté.
     */
    public function userInfo(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            Log::info('Utilisateur authentifié:', ['user' => $user]); // Enregistre les infos de l'utilisateur
            return response()->json(['user' => $user]);
        }

        Log::warning('Tentative d\'accès non authentifiée'); // Enregistre l'accès non authentifié
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

}
