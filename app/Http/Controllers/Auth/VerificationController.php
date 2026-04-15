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

        // --- الحل القاطع لمشكلة الـ ID ---

        // البحث عن أول ID متاح لا يسبب تعارض
        $idToUse = (User::max('id') ?? 0) + 1;

        // سنحاول الإضافة، وإذا فشل بسبب الـ ID، سنزيد الرقم ونحاول مرة أخرى (حتى 10 محاولات)
        $attempts = 0;
        $user = null;

        while ($attempts < 10) {
            try {
                $user = User::create([
                    'id'        => $idToUse,
                    'user_name' => $tempUser['user_name'],
                    'email'     => $tempUser['email'],
                    'phone'     => $tempUser['phone'],
                    'password'  => Hash::make($tempUser['password']),
                ]);
                break; // نجحت الإضافة، اخرج من الحلقة
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $idToUse++; // الرقم محجوز، جرب الرقم الذي يليه
                $attempts++;
            }
        }

        if (!$user) {
            return back()->with('error', 'عذراً، حدث خطأ في قاعدة البيانات، يرجى المحاولة لاحقاً.');
        }

        // محاولة تحديث العداد للمستقبل
        try {
            DB::statement("SELECT setval('users_id_seq', $idToUse)");
        } catch (\Exception $e) {
        }

        // --- نهاية الحل ---

        $verification->delete();
        session()->forget('temp_user');
        auth()->login($user);

        return redirect()->route('home')->with('success', 'تم إنشاء الحساب بنجاح!');
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
