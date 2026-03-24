<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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

    // معالجة إنشاء حساب جديد
    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('registered');
        Auth::login($user);

        return redirect('/')->with('success', 'تم إنشاء الحساب بنجاح!');
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
                $user->update();  // استخدم update بدلاً من save
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

    // تحديث الملف الشخصي
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'user_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update();  // استخدم update بدلاً من save

        return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
