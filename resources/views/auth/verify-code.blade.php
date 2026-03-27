<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحقق من بريدك - دليلي الذكي</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .verify-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .code-input {
            text-align: center;
            letter-spacing: 10px;
            font-size: 2rem;
            font-weight: bold;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="verify-card">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">تحقق من بريدك</h2>
                        <p class="text-muted">أدخل الرمز المكون من 6 أرقام المرسل إلى بريدك الإلكتروني</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('verify.code') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="code" class="form-control code-input text-center" placeholder="000000" maxlength="6" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-check-circle me-2"></i>تحقق
                        </button>

                        <div class="text-center">
                            <p class="mb-0 small">لم تصلك رسالة؟</p>
                            <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-primary">إعادة إرسال الرمز</button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
