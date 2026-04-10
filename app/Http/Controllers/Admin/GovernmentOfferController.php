<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Government;
use App\Models\GovernmentOffer;
use Illuminate\Http\Request;

class GovernmentOfferController extends Controller
{
    public function index($governmentId)
    {
        $government = Government::findOrFail($governmentId);
        $offers = $government->offers()->orderBy('created_at', 'desc')->get();

        return view('admin.government-offers', compact('government', 'offers'));
    }

    public function store(Request $request, $governmentId)
    {
        $government = Government::findOrFail($governmentId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'offer_type' => 'required|in:discount,free_service,special_feature,donation,other',
            'target_audience' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_permanent' => 'nullable|boolean',
            'terms' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
        ]);

        GovernmentOffer::create([
            'government_id' => $government->id,
            'title' => $request->title,
            'description' => $request->description,
            'offer_type' => $request->offer_type,
            'target_audience' => $request->target_audience,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_permanent' => $request->has('is_permanent'),
            'terms' => $request->terms,
            'contact_number' => $request->contact_number,
            'icon' => $request->icon ?? 'fas fa-tag',
            'is_active' => true,
        ]);

        // ✅ تصحيح: استخدم government-offers.index (بدون admin.)
 return redirect()->route('admin.government-offers.index', $government->id)
    ->with('success', 'تم إضافة العرض بنجاح');
    }

    public function update(Request $request, $governmentId, $offerId)
    {
        $offer = GovernmentOffer::where('government_id', $governmentId)->findOrFail($offerId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'offer_type' => 'required|in:discount,free_service,special_feature,donation,other',
            'target_audience' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_permanent' => 'nullable|boolean',
            'terms' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $offer->update([
            'title' => $request->title,
            'description' => $request->description,
            'offer_type' => $request->offer_type,
            'target_audience' => $request->target_audience,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_permanent' => $request->has('is_permanent'),
            'terms' => $request->terms,
            'contact_number' => $request->contact_number,
            'icon' => $request->icon ?? 'fas fa-tag',
            'is_active' => $request->has('is_active'),
        ]);

        // ✅ تصحيح: استخدم government-offers.index
return redirect()->route('admin.government-offers.index', $governmentId)
    ->with('success', 'تم تحديث العرض بنجاح');
    }

    public function destroy($governmentId, $offerId)
    {
        $offer = GovernmentOffer::where('government_id', $governmentId)->findOrFail($offerId);
        $offer->delete();

        // ✅ تصحيح: استخدم government-offers.index
return redirect()->route('admin.government-offers.index', $governmentId)
    ->with('success', 'تم حذف العرض بنجاح');
    }

    public function toggleActive($governmentId, $offerId)
    {
        $offer = GovernmentOffer::where('government_id', $governmentId)->findOrFail($offerId);
        $offer->update(['is_active' => !$offer->is_active]);

        // ✅ تصحيح: استخدم government-offers.index
return redirect()->route('admin.government-offers.index', $governmentId)
    ->with('success', 'تم تغيير حالة العرض بنجاح');
    }
}
