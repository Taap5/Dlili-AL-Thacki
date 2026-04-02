@extends('layouts.app')

@section('title', 'تأكيد تغيير البريد الإلكتروني')

@section('content')
<style>
    .confirm-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .confirm-card {
        background: white;
        border-radius: 28px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        padding: 32px;
        text-align: center;
    }

    .confirm-icon {
        width: 80px;
        height: 80px;
        background: #e8f5e9;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .confirm-icon i {
        font-size: 40px;
        color: #2e7d32;
    }

    .confirm-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1a2c3e;
    }

    .confirm-subtitle {
        color: #6c757d;
        margin-bottom: 24px;
    }

    .code-input {
        width: 100%;
        padding: 14px;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
        font-size: 1.6rem;
        font-weight: 700;
        text-align: center;
        letter-spacing: 6px;
        font-family: monospace;
        margin-bottom: 20px;
        background: #fafafa;
    }

    .code-input:focus {
        outline: none;
        border-color: #2f3e9e;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
        background: white;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border: none;
        padding: 12px;
        border-radius: 40px;
        font-weight: 600;
        color: white;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(47, 62, 158, 0.3);
    }

    .back-link {
        display: block;
        margin-top: 20px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .back-link:hover {
        color: #2f3e9e;
    }

    .alert-custom {
        border-radius: 14px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .alert-danger-custom {
        background: #ffebee;
        color: #c62828;
        border-right: 4px solid #c62828;
    }
</style>

<div class="confirm-container">
    <div class="confirm-card">
        <div class="confirm-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <h2 class="confirm-title">تأكيد تغيير البريد</h2>
        <p class="confirm-subtitle">أدخل رمز التحقق المرسل إلى بريدك الإلكتروني الجديد</p>

        @if($errors->any())
            <div class="alert-custom alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.confirm-email-change.submit') }}">
            @csrf
            <input type="text" name="code" class="code-input" placeholder="000000" maxlength="6" required autofocus>
            <button type="submit" class="btn-confirm">
                <i class="fas fa-check-circle me-2"></i>تأكيد التغيير
            </button>
        </form>

        <a href="{{ route('profile') }}" class="back-link">
            <i class="fas fa-arrow-left me-1"></i>العودة إلى الملف الشخصي
        </a>
    </div>
</div>
@endsection
