<?php

namespace App\Http\Controllers;

use App\Models\Government;
use App\Models\GovernmentCategory;
use App\Models\OfferService;
use Illuminate\Http\Request;

class GovernmentController extends Controller
{
    // عرض جميع الجهات (مع فلتر)
    public function index(Request $request)
    {
        $query = Government::with(['category', 'reviews']);

        // فلترة حسب التصنيف إذا وجد
        if ($request->has('category') && $request->category) {
            $query->where('government_category_id', $request->category);
        }

        $governments = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = GovernmentCategory::all();

        return view('pages.governments.index', compact('governments', 'categories'));
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

        return redirect()->route('governments.index')->with('success', 'تم إضافة الجهة بنجاح');
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

        return redirect()->route('governments.index')->with('success', 'تم تحديث الجهة بنجاح');
    }

    public function destroy($id)
    {
        Government::destroy($id);
        return redirect()->route('governments.index')->with('success', 'تم حذف الجهة بنجاح');
    }

    public function show($id)
    {
        $government = Government::with(['category', 'services', 'reviews.user'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        return view('pages.governments.show', compact('government'));
    }
}
