<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; // إضافة DB للتعامل مع الـ Sequence

class VerificationController extends Controller
{
    private function sendEmailViaBrevo($email, $code, $userName = 'User')
    {
        try {
            $response = Http::withHeaders([
                'api-key' => env('BREVO_API_KEY'),
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => 'دليلي الذكي',
                    'email' => 'dlilialthacki@gmail.com'
                ],
                'to' => [
                    ['email' => $email, 'name' => $userName]
                ],
                'subject' => 'رمز التحقق - دليلي الذكي',
                'htmlContent' => view('emails.verification', ['code' => $code])->render(),
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error("Brevo API Connection Error: " . $e->getMessage());
            return false;
        }
    }

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

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        $this->sendEmailViaBrevo($request->email, $code, $request->user_name);

        session(['temp_user' => $request->only('user_name', 'email', 'phone', 'password')]);

        return redirect()->route('verify.code.form')->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني');
    }

    public function showCodeForm()
    {
        if (!session()->has('temp_user')) {
            return redirect()->route('register');
        }
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $tempUser = session('temp_user');
        if (!$tempUser) {
            return redirect()->route('register')->with('error', 'انتهت الجلسة');
        }

        $verification = EmailVerification::where('email', $tempUser['email'])
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return back()->with('error', 'رمز التحقق غير صحيح');
        }

        // --- الحل الذكي: التحديث أو الإنشاء ---
        try {
            // سنبحث عن المستخدم بالإيميل، إذا وجدناه نحدث بياناته، وإذا لم نجده ننشئه
            // هذا سيتجاوز مشكلة "الإيميل مكرر" ومشكلة "الـ ID مكرر"
            $user = User::updateOrCreate(
                ['email' => $tempUser['email']], // البحث عن طريق الإيميل
                [
                    'user_name' => $tempUser['user_name'],
                    'phone'     => $tempUser['phone'],
                    'password'  => Hash::make($tempUser['password']),
                ]
            );

            // حذف الرمز وتطهير الجلسة
            $verification->delete();
            session()->forget('temp_user');

            // تسجيل الدخول
            auth()->login($user);

            return redirect()->route('home')->with('success', 'تم تسجيلك بنجاح!');
        } catch (\Exception $e) {
            \Log::error("Final DB Error: " . $e->getMessage());
            return back()->with('error', 'خطأ في قاعدة البيانات: ' . $e->getMessage());
        }
    }

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

        $this->sendEmailViaBrevo($tempUser['email'], $code, $tempUser['user_name']);

        return back()->with('success', 'تم إعادة إرسال رمز التحقق');
    }
}
