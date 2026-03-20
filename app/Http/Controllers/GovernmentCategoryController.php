<?php

namespace App\Http\Controllers;

use App\Models\GovernmentCategory;
use Illuminate\Http\Request;

class GovernmentCategoryController extends Controller
{
    public function index()
    {
        $categories = GovernmentCategory::all();
        return view('categories.index', compact('categories'));
            // جلب التصنيفات مع عدد الجهات في كل تصنيف
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

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
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

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        GovernmentCategory::destroy($id);
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
    public function show(GovernmentCategory $category)
{
    $governments = $category->governments()->with('services')->get();

    return view('pages.categories.show', compact('category', 'governments'));
}

}

