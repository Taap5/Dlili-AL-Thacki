<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Government;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['user', 'government'])->get();
        return view('favorites.index', compact('favorites'));
    }

    public function create()
    {
        $users = User::all();
        $governments = Government::all();
        return view('favorites.create', compact('users', 'governments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'government_id' => 'required|exists:governments,id',
        ]);

        Favorite::create($request->all());

        return redirect()->route('favorites.index')->with('success', 'Favorite added successfully');
    }

    public function edit($id)
    {
        $favorite = Favorite::findOrFail($id);
        $users = User::all();
        $governments = Government::all();
        return view('favorites.edit', compact('favorite', 'users', 'governments'));
    }

    public function update(Request $request, $id)
    {
        $favorite = Favorite::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'government_id' => 'required|exists:governments,id',
        ]);

        $favorite->update($request->all());

        return redirect()->route('favorites.index')->with('success', 'Favorite updated successfully');
    }

    public function destroy($id)
    {
        Favorite::destroy($id);
        return redirect()->route('favorites.index')->with('success', 'Favorite deleted successfully');
    }
}
