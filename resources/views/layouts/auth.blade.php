<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'دليلي الذكي')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2f3e9e;
            --primary-light: #5a6fc9;
            --primary-dark: #1e2a6e;
            --text-dark: #1a2c3e;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* البطاقة */
        .auth-card {
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 32px;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            transition: transform 0.2s;
        }

        /* شعار */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 6px 16px rgba(47, 62, 158, 0.25);
        }

        .logo-icon i {
            font-size: 28px;
            color: white;
        }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
        }

        .logo-subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* العناوين */
        .auth-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 24px;
            font-size: 0.85rem;
        }

        /* الحقول */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
            font-size: 0.8rem;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 42px 12px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
        }

        /* أزرار */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            padding: 12px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(47, 62, 158, 0.3);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--primary);
            padding: 10px;
            border-radius: 40px;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--primary);
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* روابط */
        .auth-link {
            text-align: center;
            margin-top: 16px;
        }

        .auth-link a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.8rem;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        /* فاصل */
        .divider {
            position: relative;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            left: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            position: relative;
            background: white;
            padding: 0 12px;
            color: #aaa;
            font-size: 0.75rem;
        }

        /* تنبيهات */
        .alert-custom {
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
        }

        .alert-success-custom {
            background: #e8f5e9;
            color: #2e7d32;
            border-right: 3px solid #2e7d32;
        }

        .alert-danger-custom {
            background: #ffebee;
            color: #c62828;
            border-right: 3px solid #c62828;
        }

        .alert-danger-custom ul {
            margin: 0;
            padding-right: 20px;
        }

        /* رمز التحقق */
        .code-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1.6rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 6px;
            font-family: monospace;
            background: #fff;
        }

        .code-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
        }

        /* استجابة */
        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .auth-card {
                padding: 24px;
                max-width: 100%;
            }

            .auth-title {
                font-size: 1.3rem;
            }

            .logo-icon {
                width: 55px;
                height: 55px;
            }

            .logo-icon i {
                font-size: 24px;
            }

            .logo-text {
                font-size: 1.2rem;
            }

            .form-control {
                padding: 10px 38px 10px 12px;
            }

            .code-input {
                font-size: 1.3rem;
                letter-spacing: 4px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        @yield('content')
    </div>
</body>
</html>
