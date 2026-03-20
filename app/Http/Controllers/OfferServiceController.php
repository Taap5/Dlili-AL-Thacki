<?php

namespace App\Http\Controllers;

use App\Models\OfferService;
use Illuminate\Http\Request;

class OfferServiceController extends Controller
{
    public function index()
    {
        $services = OfferService::all();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        OfferService::create($request->all());

        return redirect()->route('services.index')->with('success', 'Service created successfully');
    }

    public function edit($id)
    {
        $service = OfferService::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = OfferService::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service updated successfully');
    }

    public function destroy($id)
    {
        OfferService::destroy($id);
        return redirect()->route('services.index')->with('success', 'Service deleted successfully');
    }
    public function show($id)
    {
        $service = OfferService::with('governments')->findOrFail($id);
        return view('pages.services.show', compact('service'));
    }
}
