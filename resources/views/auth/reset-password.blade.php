@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور - دليلي الذكي')

@section('content')
<div class="logo-wrapper">
    <div class="logo-icon">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="logo-text">دليلي الذكي</div>
    <div class="logo-subtext">منصة الخدمات الحكومية</div>
</div>

<h2 class="auth-title">إعادة تعيين كلمة المرور</h2>
<p class="auth-subtitle">أدخل كلمة المرور الجديدة</p>

@if($errors->any())
    <div class="alert-custom alert-danger-custom">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <div class="input-group-custom">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@domain.com" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">كلمة المرور الجديدة</label>
        <div class="input-group-custom">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">تأكيد كلمة المرور الجديدة</label>
        <div class="input-group-custom">
            <i class="fas fa-check-circle"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-save me-2"></i>إعادة تعيين كلمة المرور
    </button>
</form>
@endsection
