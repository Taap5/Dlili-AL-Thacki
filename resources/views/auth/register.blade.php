<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - دليلي الذكي</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #2f3e9e;
            box-shadow: 0 0 0 0.2rem rgba(47,62,158,0.25);
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="register-card">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">إنشاء حساب جديد</h2>
                        <p class="text-muted">انضم إلينا واستمتع بجميع المزايا</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف (اختياري)</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>إنشاء حساب
                        </button>

                        <div class="text-center">
                            <p class="mb-0">لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-primary fw-bold">تسجيل الدخول</a></p>
                            <a href="/" class="btn btn-outline-secondary mt-3 w-100">
                                <i class="fas fa-arrow-left me-2"></i>العودة للصفحة الرئيسية
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
