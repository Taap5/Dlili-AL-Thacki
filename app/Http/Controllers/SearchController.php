<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Government;
use App\Models\OfferService;
use App\Models\GovernmentCategory;

class SearchController extends Controller
{
    /**
     * صفحة البحث الرئيسية + منطق البحث الجزئي والكامل مع فلاتر وترتيب
     */
    public function index(Request $request)
    {
        $query = trim($request->input('query', ''));
        $categoryId = $request->input('category_id');
        $ratingFilter = $request->input('rating'); // 1,2,3,4,5
        $sortBy = $request->input('sort', 'relevance'); // relevance, newest, rating, most_governments
        $type = $request->input('type', 'all'); // all, governments, services

        if ($query === '') {
            return view('pages.search.results', [
                'results' => collect(),
                'query' => '',
                'category_id' => $categoryId,
                'ratingFilter' => $ratingFilter,
                'sortBy' => $sortBy,
                'type' => $type,
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

        // 2️⃣ بناء الاستعلامات للبحث الجزئي
        $governmentsQuery = Government::with('reviews')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('government_category_id', $categoryId);
            })
            ->where('name', 'like', "%{$query}%");

        $servicesQuery = OfferService::with('governments')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('government_category_id', $categoryId);
            })
            ->where('name', 'like', "%{$query}%");

        // 3️⃣ فلترة حسب التقييم
        if ($ratingFilter) {
            $minRating = (int)$ratingFilter;
            $governmentsQuery->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', $minRating);

            // للخدمات، نحتاج فلترة بناءً على تقييم الجهات المرتبطة
            // (اختياري: يمكن إضافة فلتر للخدمات حسب تقييم الجهات)
        }

        // 4️⃣ ترتيب النتائج
        switch ($sortBy) {
            case 'newest':
                $governmentsQuery->orderBy('created_at', 'desc');
                $servicesQuery->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $governmentsQuery->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', 'desc');
                $servicesQuery->orderBy('name'); // الخدمات ترتب حسب الاسم
                break;
            case 'most_governments':
                $servicesQuery->withCount('governments')
                    ->orderBy('governments_count', 'desc');
                $governmentsQuery->orderBy('name');
                break;
            case 'relevance':
            default:
                $governmentsQuery->orderBy('name');
                $servicesQuery->orderBy('name');
                break;
        }

        // 5️⃣ جلب النتائج حسب نوع العرض
        $governments = $governmentsQuery->get();
        $services = $servicesQuery->get();

        // تحويل النتائج إلى تنسيق موحد
        $governmentsResults = $governments->map(fn($g) => [
            'id' => $g->id,
            'name' => $g->name,
            'type' => 'government',
            'rating' => $g->reviews->avg('rating') ?? 0,
            'reviews_count' => $g->reviews->count(),
            'image' => $g->images && count($g->images) > 0 ? $g->images[0] : null,
            'created_at' => $g->created_at,
            'governments_count' => null,
        ]);

        $servicesResults = $services->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'type' => 'service',
            'governments' => $s->governments,
            'governments_count' => $s->governments->count(),
            'created_at' => $s->created_at,
            'rating' => null,
            'reviews_count' => null,
        ]);

        // دمج النتائج حسب نوع العرض
        $results = collect();
        if ($type === 'all' || $type === 'governments') {
            $results = $results->concat($governmentsResults);
        }
        if ($type === 'all' || $type === 'services') {
            $results = $results->concat($servicesResults);
        }

        // ترتيب النتائج المدمجة حسب الترتيب المختار
        if ($sortBy === 'newest') {
            $results = $results->sortByDesc('created_at')->values();
        } elseif ($sortBy === 'rating') {
            $results = $results->sortByDesc('rating')->values();
        } elseif ($sortBy === 'most_governments') {
            $results = $results->sortByDesc('governments_count')->values();
        } else {
            $results = $results->sortBy('name')->values();
        }

        $message = $results->isEmpty() ? 'لا توجد نتائج مطابقة، جرّب البحث بكلمات أخرى.' : '';

        return view('pages.search.results', compact(
            'results', 'query', 'categoryId', 'ratingFilter', 'sortBy', 'type', 'message'
        ));
    }

    /**
     * الاقتراحات اللحظية مع عرض التقييم
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('query', '');
        $categoryId = $request->get('category_id');

        $governmentQuery = Government::with('reviews');
        $serviceQuery = OfferService::query();

        if ($categoryId) {
            $governmentQuery->where('government_category_id', $categoryId);
            $serviceQuery->where('government_category_id', $categoryId);
        }

        if ($query) {
            $governmentQuery->where('name', 'like', "%{$query}%");
            $serviceQuery->where('name', 'like', "%{$query}%");
        }

        $governments = $governmentQuery->limit(5)->get(['id', 'name']);
        $services = $serviceQuery->limit(5)->get(['id', 'name']);

        $results = [];

        foreach ($governments as $gov) {
            $avgRating = $gov->reviews->avg('rating') ?? 0;
            $results[] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'type' => 'government',
                'rating' => round($avgRating, 1),
                'reviews_count' => $gov->reviews->count()
            ];
        }

        foreach ($services as $srv) {
            $results[] = [
                'id' => $srv->id,
                'name' => $srv->name,
                'type' => 'service',
                'rating' => null,
                'reviews_count' => null
            ];
        }

        return response()->json($results);
    }
    /**
 * عرض صفحة البحث المتقدم
 */
public function advanced()
{
    return view('pages.search.advanced');
}
/**
 * البحث عبر AJAX للصفحة المتقدمة
 */
public function ajaxSearch(Request $request)
{
    $query = trim($request->input('query', ''));
    $categoryId = $request->input('category_id');
    $ratingFilter = $request->input('rating');
    $sortBy = $request->input('sort', 'relevance');
    $type = $request->input('type', 'all');
    $page = $request->input('page', 1);
    $perPage = 10;

    if ($query === '') {
        return response()->json([
            'results' => [],
            'total' => 0,
            'current_page' => 1,
            'last_page' => 1
        ]);
    }

    // بناء الاستعلامات
    $governmentsQuery = Government::with('reviews', 'category')
        ->when($categoryId, function ($q) use ($categoryId) {
            $q->where('government_category_id', $categoryId);
        })
        ->where('name', 'like', "%{$query}%");

    $servicesQuery = OfferService::with('governments', 'category')
        ->when($categoryId, function ($q) use ($categoryId) {
            $q->where('government_category_id', $categoryId);
        })
        ->where('name', 'like', "%{$query}%");

    // فلترة حسب التقييم
    if ($ratingFilter) {
        $minRating = (int)$ratingFilter;
        $governmentsQuery->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', $minRating);
    }

    // ترتيب النتائج
    switch ($sortBy) {
        case 'newest':
            $governmentsQuery->orderBy('created_at', 'desc');
            $servicesQuery->orderBy('created_at', 'desc');
            break;
        case 'rating':
            $governmentsQuery->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'desc');
            $servicesQuery->orderBy('name');
            break;
        case 'most_governments':
            $servicesQuery->withCount('governments')
                ->orderBy('governments_count', 'desc');
            $governmentsQuery->orderBy('name');
            break;
        default:
            $governmentsQuery->orderBy('name');
            $servicesQuery->orderBy('name');
            break;
    }

    // جلب النتائج
    $governments = $governmentsQuery->get();
    $services = $servicesQuery->get();

    // تحويل النتائج إلى تنسيق موحد
    $results = collect();

    if ($type === 'all' || $type === 'governments') {
        $govResults = $governments->map(fn($g) => [
            'id' => $g->id,
            'name' => $g->name,
            'type' => 'government',
            'rating' => round($g->reviews->avg('rating') ?? 0, 1),
            'reviews_count' => $g->reviews->count(),
            'address' => $g->address,
            'work_hours' => $g->work_hours,
            'category_name' => $g->category->name ?? null,
            'created_at' => $g->created_at,
        ]);
        $results = $results->concat($govResults);
    }

    if ($type === 'all' || $type === 'services') {
        $srvResults = $services->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'type' => 'service',
            'governments_count' => $s->governments->count(),
            'category_name' => $s->category->name ?? null,
            'created_at' => $s->created_at,
            'rating' => null,
            'reviews_count' => null,
            'address' => null,
            'work_hours' => null,
        ]);
        $results = $results->concat($srvResults);
    }

    // ترتيب النتائج المدمجة
    if ($sortBy === 'newest') {
        $results = $results->sortByDesc('created_at')->values();
    } elseif ($sortBy === 'rating') {
        $results = $results->sortByDesc('rating')->values();
    } elseif ($sortBy === 'most_governments') {
        $results = $results->sortByDesc('governments_count')->values();
    } else {
        $results = $results->sortBy('name')->values();
    }

    // تطبيق الترقيم
    $total = $results->count();
    $lastPage = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    $paginatedResults = $results->slice($offset, $perPage)->values();

    return response()->json([
        'results' => $paginatedResults,
        'total' => $total,
        'current_page' => $page,
        'last_page' => $lastPage,
        'per_page' => $perPage
    ]);
}
}
