@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-4x text-primary mb-2"></i>
                        <h3 class="fw-bold">{{ Auth::user()->user_name }}</h3>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="user_name" class="form-control" value="{{ Auth::user()->user_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة (اتركها فارغة إذا لم تريد التغيير)</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-danger">تسجيل الخروج</h5>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const password = document.getElementById('password')?.value;
        const confirm = document.getElementById('password_confirmation')?.value;

        if (password && password !== confirm) {
            e.preventDefault();
            alert('كلمة المرور الجديدة غير متطابقة مع تأكيدها');
        }
    });
</script>
@endpush
