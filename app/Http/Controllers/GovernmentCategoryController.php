<?php

namespace App\Http\Controllers;
use App\Models\Government;
use App\Models\GovernmentCategory;
use Illuminate\Http\Request;

class GovernmentCategoryController extends Controller
{
    public function index()
    {
        $categories = GovernmentCategory::withCount('governments')
            ->orderBy('name')
            ->get();

        return view('pages.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        GovernmentCategory::create($request->all());

        return redirect()->route('categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit($id)
    {
        $category = GovernmentCategory::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = GovernmentCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy($id)
    {
        GovernmentCategory::destroy($id);
        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }

    public function show($id)
    {
        $category = GovernmentCategory::findOrFail($id);
        $governments = Government::where('government_category_id', $id)
            ->with('reviews')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.categories.show', compact('category', 'governments'));
    }
}
