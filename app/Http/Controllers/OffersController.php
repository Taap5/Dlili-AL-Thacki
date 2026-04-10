<?php

namespace App\Http\Controllers;

use App\Models\GovernmentOffer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    public function index(Request $request)
    {
        $query = GovernmentOffer::with('government')
            ->where('is_active', true);

        // فلترة حسب نوع العرض
        if ($request->has('type') && $request->type) {
            $query->where('offer_type', $request->type);
        }

        // فلترة حسب الفئة المستهدفة
        if ($request->has('audience') && $request->audience) {
            $query->where('target_audience', 'like', '%' . $request->audience . '%');
        }

        // العروض النشطة أولاً، ثم المستمرة، ثم الأحدث
        $offers = $query->orderBy('is_permanent', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $types = [
            'discount' => 'تخفيض',
            'free_service' => 'خدمة مجانية',
            'special_feature' => 'ميزة خاصة',
            'donation' => 'تبرعات',
            'other' => 'أخرى'
        ];

        return view('pages.offers.index', compact('offers', 'types'));
    }

    public function show($id)
    {
        $offer = GovernmentOffer::with('government')->findOrFail($id);
        return view('pages.offers.show', compact('offer'));
    }
}
