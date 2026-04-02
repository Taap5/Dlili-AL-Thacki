<?php

namespace App\Http\Controllers;

use App\Models\OfferService;
use App\Models\GovernmentCategory;
use Illuminate\Http\Request;

class OfferServiceController extends Controller
{
    // عرض جميع الخدمات (مع بحث وفلتر)
    public function index(Request $request)
    {
        $query = OfferService::with(['category', 'governments']);

        // البحث حسب الاسم
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // فلترة حسب التصنيف إذا وجد
        if ($request->has('category') && $request->category) {
            $query->where('government_category_id', $request->category);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = GovernmentCategory::all();

        return view('pages.services.index', compact('services', 'categories'));
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
            'category_id' => 'nullable|exists:government_categories,id',
        ]);

        OfferService::create([
            'name' => $request->name,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ]);

        return redirect()->route('services.index')->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function edit($id)
    {
        $service = OfferService::findOrFail($id);
        $categories = GovernmentCategory::all();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $service = OfferService::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:government_categories,id',
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ]);

        return redirect()->route('services.index')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy($id)
    {
        OfferService::destroy($id);
        return redirect()->route('services.index')->with('success', 'تم حذف الخدمة بنجاح');
    }

    public function show($id)
    {
        $service = OfferService::with(['governments' => function($query) {
            $query->withPivot('description', 'contact_number', 'work_hours', 'price');
        }, 'category'])->findOrFail($id);

        // جلب خدمات مشابهة (نفس التصنيف)
        $relatedServices = OfferService::where('government_category_id', $service->government_category_id)
            ->where('id', '!=', $id)
            ->with('governments')
            ->limit(4)
            ->get();

        return view('pages.services.show', compact('service', 'relatedServices'));
    }
}
