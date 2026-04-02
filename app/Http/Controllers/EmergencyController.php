<?php

namespace App\Http\Controllers;

use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmergencyController extends Controller
{
    /**
     * جلب أقرب المستشفيات حسب موقع المستخدم
     */
    public function nearest(Request $request)
    {
        try {
            $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);

            $userLat = $request->lat;
            $userLng = $request->lng;

            // جلب جميع المستشفيات (تصنيف id = 1)
            $hospitals = Government::where('government_category_id', 1)
                ->whereNotNull('location_lat')
                ->whereNotNull('location_long')
                ->get();

            if ($hospitals->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد مستشفيات مسجلة في قاعدة البيانات'
                ]);
            }

            // حساب المسافة الخطية لكل مستشفى
            $results = [];
            foreach ($hospitals as $hospital) {
                $distance = $this->calculateLinearDistance(
                    $userLat, $userLng,
                    $hospital->location_lat, $hospital->location_long
                );

                $results[] = [
                    'id' => $hospital->id,
                    'name' => $hospital->name,
                    'address' => $hospital->address,
                    'contact_number' => $hospital->contact_number,
                    'work_hours' => $hospital->work_hours,
                    'distance' => $distance,
                    'duration' => $distance * 2, // تقدير تقريبي للزمن
                    'lat' => $hospital->location_lat,
                    'lng' => $hospital->location_long,
                    'image' => $hospital->images && count($hospital->images) > 0 ? $hospital->images[0] : null,
                ];
            }

            // ترتيب حسب المسافة
            $results = collect($results)->sortBy('distance')->take(5)->values();

            return response()->json([
                'success' => true,
                'hospitals' => $results,
                'user_location' => [
                    'lat' => $userLat,
                    'lng' => $userLng
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حساب المسافة الخطية (مباشرة) بين نقطتين
     */
    private function calculateLinearDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
