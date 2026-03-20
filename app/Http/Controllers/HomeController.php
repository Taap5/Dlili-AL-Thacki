<?php

namespace App\Http\Controllers;

use App\Models\GovernmentCategory;
use App\Models\Government;
use Illuminate\Http\Request;
use App\Models\OfferService;

class HomeController extends Controller
{
  public function index(Request $request)
{
    $categoryId = $request->get('category_id');

    // جلب الخدمات
    $servicesQuery = OfferService::query();
    if ($categoryId) {
        $servicesQuery->where('government_category_id', $categoryId);
    }
    $services = $servicesQuery->get(['id', 'name'])
        ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => 'service']);

    // جلب الجهات
    $govQuery = Government::query();
    if ($categoryId) {
        $govQuery->where('government_category_id', $categoryId);
    }
    $governments = $govQuery->get(['id', 'name'])
        ->map(fn($g) => ['id' => $g->id, 'name' => $g->name, 'type' => 'government']);

    // دمجهم معًا
    $allSuggestions = $services->concat($governments);

    // اختيار 5 اقتراحات عشوائية
    $searchSuggestions = $allSuggestions->shuffle()->take(5);

    $categories = GovernmentCategory::withCount('governments')->get();

    return view('pages.home.index', compact('searchSuggestions', 'categories'));
}

}
