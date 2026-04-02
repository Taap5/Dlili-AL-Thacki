@extends('layouts.auth')

@section('title', 'تسجيل الدخول - دليلي الذكي')

@section('content')
<div class="logo-wrapper">
    <div class="logo-icon">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="logo-text">دليلي الذكي</div>
    <div class="logo-subtext">منصة الخدمات الحكومية</div>
</div>

<h2 class="auth-title">مرحباً بك</h2>
<p class="auth-subtitle">سجل دخولك للوصول إلى الخدمات</p>

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

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <div class="input-group-custom">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@domain.com" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">كلمة المرور</label>
        <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="remember" id="remember">
        <label class="form-check-label" for="remember">تذكرني</label>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
    </button>

    <div class="auth-link">
        <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
    </div>

    <div class="divider">
        <span>أو</span>
    </div>

    <a href="{{ route('register') }}" class="btn-secondary">
        <i class="fas fa-user-plus me-2"></i>إنشاء حساب جديد
    </a>

    <div class="auth-link">
        <a href="/">
            <i class="fas fa-arrow-left me-1"></i>العودة للصفحة الرئيسية
        </a>
    </div>
</form>
@endsection
