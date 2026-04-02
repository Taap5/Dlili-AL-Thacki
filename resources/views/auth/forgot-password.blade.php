@extends('layouts.auth')

@section('title', 'نسيت كلمة المرور - دليلي الذكي')

@section('content')
<div class="logo-wrapper">
    <div class="logo-icon">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="logo-text">دليلي الذكي</div>
    <div class="logo-subtext">منصة الخدمات الحكومية</div>
</div>

<h2 class="auth-title">نسيت كلمة المرور؟</h2>
<p class="auth-subtitle">أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين</p>

@if(session('success'))
    <div class="alert-custom alert-success-custom">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <div class="input-group-custom">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@domain.com" required autofocus>
        </div>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-paper-plane me-2"></i>إرسال رابط إعادة التعيين
    </button>

    <div class="auth-link">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left me-1"></i>العودة إلى تسجيل الدخول
        </a>
    </div>
</form>
@endsection
