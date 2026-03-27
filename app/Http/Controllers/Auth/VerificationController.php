<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    // إرسال رمز التحقق
    public function sendCode(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        // حفظ الرمز
        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        // إرسال البريد
        Mail::send('emails.verification', ['code' => $code], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('رمز التحقق - دليلي الذكي');
        });

        // تخزين بيانات المستخدم المؤقتة في الجلسة
        session(['temp_user' => $request->only('user_name', 'email', 'phone', 'password')]);

        return redirect()->route('verify.code.form')->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني');
    }

    // عرض صفحة إدخال الرمز
    public function showCodeForm()
    {
        if (!session()->has('temp_user')) {
            return redirect()->route('register');
        }
        return view('auth.verify-code');
    }

    // التحقق من الرمز
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $tempUser = session('temp_user');
        if (!$tempUser) {
            return redirect()->route('register')->with('error', 'انتهت الجلسة، يرجى المحاولة مرة أخرى');
        }

        $verification = EmailVerification::where('email', $tempUser['email'])
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return back()->with('error', 'رمز التحقق غير صحيح');
        }

        if ($verification->isExpired()) {
            $verification->delete();
            session()->forget('temp_user');
            return redirect()->route('register')->with('error', 'انتهت صلاحية الرمز، يرجى إعادة التسجيل');
        }

        // إنشاء المستخدم
        $user = User::create([
            'user_name' => $tempUser['user_name'],
            'email' => $tempUser['email'],
            'phone' => $tempUser['phone'],
            'password' => Hash::make($tempUser['password']),
        ]);

        $user->assignRole('registered');

        // حذف رمز التحقق
        $verification->delete();
        session()->forget('temp_user');

        // تسجيل الدخول تلقائياً
        auth()->login($user);

        return redirect()->route('home')->with('success', 'تم إنشاء الحساب بنجاح!');
    }

    // إعادة إرسال الرمز
    public function resendCode()
    {
        $tempUser = session('temp_user');
        if (!$tempUser) {
            return redirect()->route('register')->with('error', 'انتهت الجلسة، يرجى المحاولة مرة أخرى');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        EmailVerification::updateOrCreate(
            ['email' => $tempUser['email']],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        Mail::send('emails.verification', ['code' => $code], function ($message) use ($tempUser) {
            $message->to($tempUser['email'])
                    ->subject('رمز التحقق - دليلي الذكي');
        });

        return back()->with('success', 'تم إعادة إرسال رمز التحقق');
    }
}
