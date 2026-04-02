@extends('layouts.auth')

@section('title', 'تحقق من بريدك - دليلي الذكي')

@section('content')
<div class="logo-wrapper">
    <div class="logo-icon">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="logo-text">دليلي الذكي</div>
    <div class="logo-subtext">منصة الخدمات الحكومية</div>
</div>

<h2 class="auth-title">تحقق من بريدك</h2>
<p class="auth-subtitle">أدخل الرمز المكون من 6 أرقام المرسل إلى بريدك الإلكتروني</p>

@if(session('success'))
    <div class="alert-custom alert-success-custom">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('verify.code') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">رمز التحقق</label>
        <input type="text" name="code" class="code-input" placeholder="000000" maxlength="6" required autofocus>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-check-circle me-2"></i>تحقق
    </button>

    <div class="divider">
        <span>أو</span>
    </div>

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn-secondary">
            <i class="fas fa-paper-plane me-2"></i>إعادة إرسال الرمز
        </button>
    </form>
</form>
@endsection
