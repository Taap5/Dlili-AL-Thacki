<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCode;

class AuthController extends Controller
{
    // عرض صفحة تسجيل الدخول
    public function showLogin()
    {
        return view('auth.login');
    }

    // معالجة تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // توجيه المسؤول إلى لوحة التحكم
            if (Auth::user()->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            }

            // توجيه المستخدم العادي إلى الصفحة الرئيسية
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    // عرض صفحة إنشاء حساب
    public function showRegister()
    {
        return view('auth.register');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    // عرض صفحة نسيت كلمة المرور
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // إرسال رابط إعادة تعيين كلمة المرور
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // عرض صفحة إعادة تعيين كلمة المرور
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // معالجة إعادة تعيين كلمة المرور
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->update();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // عرض صفحة الملف الشخصي
    public function showProfile()
    {
        return view('profile.index');
    }

    // تحديث الملف الشخصي (بدون تغيير البريد)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'user_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'current_password' => 'nullable|string|required_with:password',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // التحقق من كلمة المرور الحالية إذا كان المستخدم يريد تغيير كلمة المرور
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
        }

        // رفع الصورة الشخصية
        if ($request->hasFile('profile_photo')) {
            // حذف الصورة القديمة إذا وجدت
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        $user->user_name = $request->user_name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    // طلب تغيير البريد الإلكتروني
    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
            'current_password' => 'required',
        ]);

        $user = Auth::user();

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // إنشاء رمز تحقق
        $code = rand(100000, 999999);

        // تخزين الرمز في الجلسة
        session([
            'pending_email_change' => $request->new_email,
            'email_verification_code' => $code,
            'email_verification_expires' => now()->addMinutes(10)
        ]);

        // إرسال رمز التحقق إلى البريد الجديد
        Mail::to($request->new_email)->send(new EmailVerificationCode($code));

        return redirect()->route('profile')->with('warning', 'تم إرسال رمز التحقق إلى البريد الإلكتروني الجديد. الرمز صالح لمدة 10 دقائق.');
    }

    // عرض صفحة تأكيد تغيير البريد
    public function showConfirmEmailChange()
    {
        if (!session('pending_email_change')) {
            return redirect()->route('profile')->with('error', 'لا يوجد طلب تغيير بريد نشط');
        }
        return view('auth.confirm-email-change');
    }

    // تأكيد تغيير البريد الإلكتروني
    public function confirmEmailChange(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $pendingEmail = session('pending_email_change');
        $storedCode = session('email_verification_code');
        $expires = session('email_verification_expires');

        if (!$pendingEmail || !$storedCode || now()->gt($expires)) {
            return redirect()->route('profile')->withErrors(['code' => 'طلب تغيير البريد غير صالح أو انتهت صلاحيته']);
        }

        if ($request->code != $storedCode) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح']);
        }

        $user = Auth::user();
        $user->email = $pendingEmail;
        $user->email_verified_at = null; // إعادة تعيين التحقق
        $user->save();

        // مسح الجلسة
        session()->forget(['pending_email_change', 'email_verification_code', 'email_verification_expires']);

        return redirect()->route('profile')->with('success', 'تم تغيير البريد الإلكتروني بنجاح. يرجى التحقق من بريدك الجديد لتأكيده.');
    }

    // حذف الصورة الشخصية
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return response()->json(['success' => true]);
    }
}
