<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Government;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'government'])->get();
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $users = User::all();
        $governments = Government::all();
        return view('reviews.create', compact('users', 'governments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'government_id' => 'required|exists:governments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create($request->all());

        return redirect()->route('reviews.index')->with('success', 'Review added successfully');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $users = User::all();
        $governments = Government::all();
        return view('reviews.edit', compact('review', 'users', 'governments'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'government_id' => 'required|exists:governments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully');
    }

    public function destroy($id)
    {
        Review::destroy($id);
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }
}
