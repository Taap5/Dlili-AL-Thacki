@extends('layouts.auth')

@section('title', 'إنشاء حساب - دليلي الذكي')

@section('content')
<div class="logo-wrapper">
    <div class="logo-icon">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="logo-text">دليلي الذكي</div>
    <div class="logo-subtext">منصة الخدمات الحكومية</div>
</div>

<h2 class="auth-title">إنشاء حساب جديد</h2>
<p class="auth-subtitle">انضم إلينا واستمتع بجميع المزايا</p>

@if($errors->any())
    <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">الاسم</label>
        <div class="input-group-custom">
            <i class="fas fa-user"></i>
            <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" placeholder="أدخل اسمك الكامل" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <div class="input-group-custom">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@domain.com" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">رقم الهاتف <span class="text-muted">(اختياري)</span></label>
        <div class="input-group-custom">
            <i class="fas fa-phone-alt"></i>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="05xxxxxxxx">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">كلمة المرور</label>
        <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">تأكيد كلمة المرور</label>
        <div class="input-group-custom">
            <i class="fas fa-check-circle"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-paper-plane me-2"></i>إنشاء حساب
    </button>

    <div class="divider">
        <span>أو</span>
    </div>

    <a href="{{ route('login') }}" class="btn-secondary">
        <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
    </a>

    <div class="auth-link">
        <a href="/">
            <i class="fas fa-arrow-left me-1"></i>العودة للصفحة الرئيسية
        </a>
    </div>
</form>
@endsection
