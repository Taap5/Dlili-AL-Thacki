<?php

namespace App\Http\Controllers;

use App\Models\Government;
use App\Models\OfferService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // عرض صفحة المفضلة
    public function index()
    {
        $user = Auth::user();
        $governments = $user->favoriteGovernments;
        $services = $user->favoriteServices;

        return view('profile.favorites', compact('governments', 'services'));
    }

    // تبديل حالة المفضلة للجهات
    public function toggleGovernment(Request $request)
    {
        try {
            $request->validate([
                'government_id' => 'required|exists:governments,id',
            ]);

            $user = Auth::user();
            $governmentId = $request->government_id;

            // التحقق إذا كانت الجهة مفضلة بالفعل
            $exists = $user->favoriteGovernments()->where('government_id', $governmentId)->exists();

            if ($exists) {
                $user->favoriteGovernments()->detach($governmentId);
                $isFavorited = false;
                $message = 'تم الإزالة من المفضلة';
            } else {
                $user->favoriteGovernments()->attach($governmentId);
                $isFavorited = true;
                $message = 'تم الإضافة إلى المفضلة';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_favorited' => $isFavorited,
                    'message' => $message,
                    'type' => 'government'
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'حدث خطأ');
        }
    }

    // تبديل حالة المفضلة للخدمات
    public function toggleService(Request $request)
    {
        try {
            $request->validate([
                'service_id' => 'required|exists:offer_services,id',
            ]);

            $user = Auth::user();
            $serviceId = $request->service_id;

            // التحقق إذا كانت الخدمة مفضلة بالفعل
            $exists = $user->favoriteServices()->where('service_id', $serviceId)->exists();

            if ($exists) {
                $user->favoriteServices()->detach($serviceId);
                $isFavorited = false;
                $message = 'تم الإزالة من المفضلة';
            } else {
                $user->favoriteServices()->attach($serviceId);
                $isFavorited = true;
                $message = 'تم الإضافة إلى المفضلة';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_favorited' => $isFavorited,
                    'message' => $message,
                    'type' => 'service'
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'حدث خطأ');
        }
    }
}
