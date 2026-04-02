@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --bg-light: #fef9f0;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
    }

    .profile-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .profile-card {
        background: #fff;
        border-radius: 32px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        padding: 40px 30px 70px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .profile-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .profile-avatar img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: white;
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 4px solid rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        backdrop-filter: blur(4px);
    }

    .profile-avatar-placeholder i {
        font-size: 48px;
        color: white;
    }

    .avatar-actions {
        position: absolute;
        bottom: 5px;
        right: 5px;
        display: flex;
        gap: 8px;
    }

    .avatar-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .avatar-btn.camera {
        color: var(--primary);
    }

    .avatar-btn.trash {
        color: #dc3545;
    }

    .avatar-btn:hover {
        transform: scale(1.1);
    }

    .profile-name {
        color: white;
        margin: 15px 0 5px;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .profile-email {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        margin: 0;
    }

    .profile-body {
        padding: 30px;
        margin-top: -30px;
        background: #fff;
        position: relative;
        z-index: 2;
    }

    .form-section {
        margin-bottom: 25px;
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eef2f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title i {
        color: var(--primary);
    }

    .form-label-custom {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 6px;
        font-size: 0.85rem;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
        font-size: 0.9rem;
        transition: all 0.2s;
        font-family: "Cairo", sans-serif;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .form-control-custom:disabled {
        background: #f5f5f5;
        cursor: not-allowed;
    }

    .form-text-custom {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .email-change-status {
        background: #fef9e6;
        border-radius: 14px;
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .email-change-status p {
        margin: 0;
        font-size: 0.75rem;
        color: #856404;
    }

    .email-change-status a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .email-change-status a:hover {
        text-decoration: underline;
    }

    .btn-request-change {
        background: #ffc107;
        border: none;
        border-radius: 30px;
        padding: 6px 16px;
        font-size: 0.7rem;
        font-weight: 500;
        color: #856404;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-request-change:hover {
        background: #e0a800;
        color: white;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 40px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
    }

    .logout-section {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .logout-title {
        font-weight: 600;
        color: #dc3545;
        margin: 0;
        font-size: 1rem;
    }

    .btn-logout {
        background: transparent;
        border: 1px solid #dc3545;
        border-radius: 40px;
        padding: 8px 24px;
        color: #dc3545;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-logout:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-1px);
    }

    /* تنسيقات المودال */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 28px;
        max-width: 450px;
        width: 90%;
        margin: 0 auto;
        overflow: hidden;
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.2s;
    }

    .close-modal:hover {
        color: #dc3545;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-body .form-group {
        margin-bottom: 20px;
    }

    .modal-body .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .modal-body .form-control-custom {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.9rem;
    }

    .modal-body .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .modal-body .btn-save {
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 40px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-body .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
    }

    /* تنبيهات */
    .alert-custom {
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success-custom {
        background: #e8f5e9;
        color: #2e7d32;
        border-right: 4px solid #2e7d32;
    }

    .alert-danger-custom {
        background: #ffebee;
        color: #c62828;
        border-right: 4px solid #c62828;
    }

    .alert-warning-custom {
        background: #fff3e0;
        color: #856404;
        border-right: 4px solid #ffc107;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 30px 20px 60px;
        }

        .profile-body {
            padding: 25px 20px;
        }

        .profile-avatar img,
        .profile-avatar-placeholder {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 1.3rem;
        }

        .modal-content {
            width: 95%;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <!-- رأس الملف الشخصي -->
        <div class="profile-header">
            <div class="profile-avatar">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->user_name }}">
                @else
                    <div class="profile-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div class="avatar-actions">
                    <label for="profile_photo" class="avatar-btn camera">
                        <i class="fas fa-camera"></i>
                    </label>
                    @if(Auth::user()->profile_photo)
                        <button type="button" class="avatar-btn trash" onclick="removePhoto()">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
            <h2 class="profile-name">{{ Auth::user()->user_name }}</h2>
            <p class="profile-email">{{ Auth::user()->email }}</p>
        </div>

        <!-- محتوى الملف الشخصي -->
        <div class="profile-body">
            @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert-custom alert-warning-custom">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-custom alert-danger-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                <small class="text-muted d-block mb-3 text-center">يُسمح بصور JPG, PNG, GIF بحجم أقصى 2MB</small>

                <!-- المعلومات الشخصية -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user-circle"></i>
                        <span>المعلومات الشخصية</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">الاسم</label>
                        <input type="text" name="user_name" class="form-control-custom" value="{{ Auth::user()->user_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control-custom" value="{{ Auth::user()->email }}" disabled>
                        <div class="form-text-custom">لا يمكن تغيير البريد الإلكتروني مباشرة. لتغييره، اضغط على زر "طلب تغيير البريد" أدناه.</div>

                        @if(session('pending_email_change'))
                            <div class="email-change-status">
                                <p><i class="fas fa-clock me-1"></i> تم إرسال رمز التحقق إلى البريد الجديد. <a href="{{ route('profile.confirm-email-change') }}">انقر هنا لإدخال الرمز</a></p>
                            </div>
                        @else
                            <button type="button" class="btn-request-change mt-2" id="changeEmailBtn">
                                <i class="fas fa-envelope"></i> طلب تغيير البريد الإلكتروني
                            </button>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control-custom" value="{{ Auth::user()->phone }}">
                    </div>
                </div>

                <!-- تغيير كلمة المرور -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-lock"></i>
                        <span>تغيير كلمة المرور</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">كلمة المرور الحالية *</label>
                        <input type="password" name="current_password" class="form-control-custom" required>
                        <div class="form-text-custom">مطلوب لتأكيد هويتك قبل تغيير كلمة المرور</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control-custom" id="password">
                        <div class="form-text-custom">اتركها فارغة إذا لم تريد التغيير</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="password_confirmation" class="form-control-custom" id="password_confirmation">
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
            </form>

            <!-- تسجيل الخروج -->
            <div class="logout-section">
                <h5 class="logout-title">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    تسجيل الخروج
                </h5>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal طلب تغيير البريد الإلكتروني -->
<div id="emailChangeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>طلب تغيير البريد الإلكتروني</h3>
            <button type="button" class="close-modal" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="emailChangeForm" method="POST" action="{{ route('profile.request-email-change') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني الجديد</label>
                    <input type="email" name="new_email" class="form-control-custom" required placeholder="example@domain.com">
                </div>
                <div class="form-group">
                    <label class="form-label">كلمة المرور الحالية</label>
                    <input type="password" name="current_password" class="form-control-custom" required>
                </div>
                <button type="submit" class="btn-save">إرسال رمز التحقق</button>
            </form>
        </div>
    </div>
</div>

<script>
    // رفع الصورة فور الاختيار
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            this.closest('form').submit();
        }
    });

    // حذف الصورة الشخصية
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

    // التحقق من تطابق كلمة المرور قبل الإرسال
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const password = document.getElementById('password')?.value;
        const confirm = document.getElementById('password_confirmation')?.value;

        if (password && password !== confirm) {
            e.preventDefault();
            alert('كلمة المرور الجديدة غير متطابقة مع تأكيدها');
        }
    });

    // فتح المودال
    function openEmailModal() {
        const modal = document.getElementById('emailChangeModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('show');
        }
    }

    // إغلاق المودال
    function closeEmailModal() {
        const modal = document.getElementById('emailChangeModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
        }
    }

    // ربط زر الفتح
    const changeEmailBtn = document.getElementById('changeEmailBtn');
    if (changeEmailBtn) {
        changeEmailBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openEmailModal();
        });
    }

    // ربط زر الإغلاق
    const closeModalBtn = document.getElementById('closeModalBtn');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeEmailModal);
    }

    // إغلاق المودال عند الضغط خارج المحتوى
    window.onclick = function(event) {
        const modal = document.getElementById('emailChangeModal');
        if (event.target === modal) {
            closeEmailModal();
        }
    }

    // إغلاق المودال عند الضغط على ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmailModal();
        }
    });
</script>
@endsection
