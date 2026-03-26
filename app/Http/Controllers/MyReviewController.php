<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyReviewController extends Controller
{
    // عرض تقييمات المستخدم الحالي
    public function index()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->with('government')->orderBy('created_at', 'desc')->get();

        return view('profile.my-reviews', compact('reviews'));
    }

    // حذف تقييم
    public function destroy($id)
    {
        $review = Auth::user()->reviews()->findOrFail($id);
        $review->delete();

        return redirect()->route('my.reviews')->with('success', 'تم حذف التقييم بنجاح');
    }
}
