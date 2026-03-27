@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- قسم الصورة الشخصية -->
                    <div class="text-center mb-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                 class="rounded-circle mb-2"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #2f3e9e;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2"
                                 style="width: 120px; height: 120px; border: 3px solid #2f3e9e;">
                                <i class="fas fa-user-circle fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <label for="profile_photo" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-camera me-1"></i> تغيير الصورة
                            </label>
                            @if(Auth::user()->profile_photo)
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePhoto()">
                                    <i class="fas fa-trash me-1"></i> حذف
                                </button>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-1">يُسمح بصور JPG, PNG, GIF بحجم أقصى 2MB</small>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">

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
                            <label class="form-label">كلمة المرور الحالية *</label>
                            <input type="password" name="current_password" class="form-control" required>
                            <small class="text-muted">مطلوب لتأكيد هويتك قبل تغيير كلمة المرور</small>
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
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            this.closest('form').submit();
        }
    });

    function removePhoto() {
        if (confirm('هل أنت متأكد من حذف الصورة الشخصية؟')) {
            fetch('{{ route('profile.remove-photo') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }

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
