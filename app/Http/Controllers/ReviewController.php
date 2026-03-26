<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // إضافة تقييم جديد
    public function store(Request $request)
    {
        // التأكد من أن الاستجابة ستكون JSON
        if (!$request->ajax() && !$request->wantsJson()) {
            return back()->with('error', 'طلب غير صالح');
        }

        try {
            $request->validate([
                'government_id' => 'required|exists:governments,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            // التأكد من أن المستخدم لم يضف تقييماً لهذه الجهة من قبل
            $existingReview = Review::where('user_id', Auth::id())
                ->where('government_id', $request->government_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'لقد قمت بتقييم هذه الجهة مسبقاً. يمكنك تعديل تقييمك السابق.'
                ], 400);
            }

            $review = Review::create([
                'user_id' => Auth::id(),
                'government_id' => $request->government_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // تحميل المستخدم المرتبط بالتقييم
            $review->load('user');

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة تقييمك بنجاح',
                'review' => [
                    'id' => $review->id,
                    'user_name' => $review->user->user_name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->diffForHumans(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    // عرض التقييمات (API)
    public function getReviews($governmentId)
    {
        $reviews = Review::where('government_id', $governmentId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return response()->json([
            'success' => true,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'reviews' => $reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->user_name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_owner' => Auth::check() && $review->user_id == Auth::id(),
                ];
            })
        ]);
    }

    // تحديث تقييم
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا التقييم'
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث تقييمك بنجاح',
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'updated_at' => $review->updated_at->diffForHumans(),
            ]
        ]);
    }

    // حذف تقييم
    public function destroy($id, Request $request)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا التقييم'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف تقييمك بنجاح'
        ]);
    }
}
