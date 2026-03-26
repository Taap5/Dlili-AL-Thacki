<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Government;
use App\Models\OfferService;
use App\Models\GovernmentCategory;

class SearchController extends Controller
{
    /**
     * صفحة البحث الرئيسية + منطق البحث الجزئي والكامل
     */
    public function index(Request $request)
    {
        $query = trim($request->input('query', ''));
        $categoryId = $request->input('category_id');

        if ($query === '') {
            return view('pages.search.results', [
                'results' => collect(),
                'query' => '',
                'category_id' => $categoryId,
                'message' => ''
            ]);
        }

        // 1️⃣ مطابقة تامة
        $exactGovernment = Government::when($categoryId, function ($q) use ($categoryId) {
            $q->where('government_category_id', $categoryId);
        })->where('name', $query)->first();

        if ($exactGovernment) {
            return redirect()->route('governments.show', $exactGovernment->id);
        }

        $exactService = OfferService::when($categoryId, function ($q) use ($categoryId) {
            $q->where('government_category_id', $categoryId);
        })->where('name', $query)->first();

        if ($exactService) {
            return redirect()->route('services.show', $exactService->id);
        }

        // 2️⃣ بحث جزئي
        $governments = Government::with('reviews')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('government_category_id', $categoryId);
            })->where('name', 'like', "%{$query}%")->get();

        $services = OfferService::when($categoryId, function ($q) use ($categoryId) {
            $q->where('government_category_id', $categoryId);
        })->where('name', 'like', "%{$query}%")->with('governments')->get();

        $results = collect()
            ->concat($governments->map(fn($g) => [
                'id' => $g->id,
                'name' => $g->name,
                'type' => 'government'
            ]))
            ->concat($services->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'type' => 'service',
                'governments' => $s->governments
            ]))
            ->values();

        $message = $results->isEmpty() ? 'لا توجد نتائج مطابقة، جرّب البحث بكلمات أخرى.' : '';

        return view('pages.search.results', compact('results', 'query', 'categoryId', 'message'));
    }

    /**
     * الاقتراحات اللحظية
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('query', '');
        $categoryId = $request->get('category_id');

        $governmentQuery = Government::query();
        $serviceQuery = OfferService::query();

        if ($categoryId) {
            $governmentQuery->where('government_category_id', $categoryId);
            $serviceQuery->where('government_category_id', $categoryId);
        }

        if ($query) {
            $governmentQuery->where('name', 'like', "%{$query}%");
            $serviceQuery->where('name', 'like', "%{$query}%");
        }

        $governments = $governmentQuery->get(['id', 'name']);
        $services = $serviceQuery->get(['id', 'name']);

        $results = [];

        foreach ($governments as $gov) {
            $results[] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'type' => 'government'
            ];
        }

        foreach ($services as $srv) {
            $results[] = [
                'id' => $srv->id,
                'name' => $srv->name,
                'type' => 'service'
            ];
        }

        return response()->json($results);
    }
}
