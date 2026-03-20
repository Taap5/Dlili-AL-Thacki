<?php

namespace App\Http\Controllers;

use App\Models\Government;
use App\Models\GovernmentCategory;
use App\Models\OfferService;
use Illuminate\Http\Request;

class GovernmentController extends Controller
{
    public function index()
    {
        $governments = Government::with(['category', 'services', 'reviews', 'favoritedByUsers'])->get();
        return view('governments.index', compact('governments'));
    }

    public function create()
    {
        $categories = GovernmentCategory::all();
        $services = OfferService::all();
        return view('governments.create', compact('categories', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_long' => 'nullable|numeric',
            'contact_number' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'government_category_id' => 'required|exists:government_categories,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:offer_services,id',
        ]);

        $government = Government::create($request->all());

        if ($request->has('services')) {
            $government->services()->sync($request->services);
        }

        return redirect()->route('governments.index')->with('success', 'Government created successfully');
    }

    public function edit($id)
    {
        $government = Government::findOrFail($id);
        $categories = GovernmentCategory::all();
        $services = OfferService::all();
        return view('governments.edit', compact('government', 'categories', 'services'));
    }

    public function update(Request $request, $id)
    {
        $government = Government::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_long' => 'nullable|numeric',
            'contact_number' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'government_category_id' => 'required|exists:government_categories,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:offer_services,id',
        ]);

        $government->update($request->all());

        if ($request->has('services')) {
            $government->services()->sync($request->services);
        }

        return redirect()->route('governments.index')->with('success', 'Government updated successfully');
    }

    public function destroy($id)
    {
        Government::destroy($id);
        return redirect()->route('governments.index')->with('success', 'Government deleted successfully');
    }
    public function show($id)
    {
        $government = Government::with(['category', 'services', 'reviews'])->findOrFail($id);
        return view('pages.governments.show', compact('government'));
    }
}
