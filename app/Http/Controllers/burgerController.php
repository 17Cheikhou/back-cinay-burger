<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;

class burgerController extends Controller
{
    public function getBurgers(Request $request)
    {
        if ($request->query('archive') === 'false') {
            $burgers = Burger::where('archive', false)->get();
        } else {
            $burgers = Burger::all();
        }

        return response()->json($burgers);
    }

    public function search(Request $request)
    {
        $query = Burger::query();

        if ($request->has('nom')) {
            $query->where('nom', 'like', '%' . $request->input('nom') . '%');
        }

        if ($request->has('date_creation')) {
            $query->whereDate('created_at', $request->input('date_creation'));
        }


        if ($request->has('archive')) {
            $query->where('archive', filter_var($request->input('archive'), FILTER_VALIDATE_BOOLEAN));
        }


        if ($request->has('client_nom')) {
            $query->whereHas('commandes', function($q) use ($request) {
                $q->where('nom_client', 'like', '%' . $request->input('client_nom') . '%');
            });
        }

                $burgers = $query->get();


        $burgers->map(function ($burger) {
            if ($burger->image) {
                $burger->image_url = url('images/' . $burger->image);
            } else {
                $burger->image_url = null;
            }
            return $burger;
        });

        return response()->json($burgers);
    }


    public function index()
    {
        $burgers = Burger::all();


        $burgers->map(function ($burger) {
            if ($burger->image) {
                $burger->image_url = url('images/' . $burger->image);
            } else {
                $burger->image_url = null;
            }
            return $burger;
        });

        return response()->json($burgers, 200);
    }


    public function getArchived()
    {
        $archivedBurgers = Burger::where('archive', 1)->get();


        $archivedBurgers->map(function ($burger) {
            if ($burger->image) {
                $burger->image_url = url('images/' . $burger->image);
            } else {
                $burger->image_url = null;
            }
            return $burger;
        });

        return response()->json($archivedBurgers, 200);
    }

    public function changeStatut($id)
    {
        $burger = Burger::findOrFail($id);
        $burger->archive = !$burger->archive;
        $burger->save();

        return response()->json($burger, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'archive' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
        ]);


        if ($request->hasFile('image')) {
            Log::info('Fichier d\'image reçu : ' . $request->file('image')->getClientOriginalName());

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            \Log::info('Aucun fichier d\'image reçu.');
            $imageName = null;
        }

        $burger = new Burger();
        $burger->nom = $request->nom;
        $burger->prix = $request->prix;
        $burger->description = $request->description;
        $burger->archive = $request->archive;
        $burger->image = $imageName; // Enregistre le nom de l'image dans la DB
        $burger->save();

        return response()->json($burger, 201);
    }


    public function show(string $id)
    {
        $burger = Burger::findOrFail($id);
        if ($burger->image) {
            $burger->image_url = url('images/' . $burger->image);
        } else {
            $burger->image_url = null;
        }
        return response()->json($burger, 200);

    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'required|string',
            'archive' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $burger = Burger::findOrFail($id);

        $data = $request->all();

        $data['archive'] = filter_var($data['archive'], FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $burger->update($data);

        return response()->json($burger, 200);
    }

    public function destroy(string $id)
    {
        try {
            $burger = Burger::findOrFail($id);
            $burger->delete();
            return response()->json(['message' => 'Burger supprimé avec succès.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Loggez l'erreur pour debug
            \Log::error('Erreur lors de la suppression du burger: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur de suppression.'], 500);
        }

    }
}
