<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق - دليلي الذكي</title>
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
            font-family: 'Cairo', 'Tahoma', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* البطاقة الرئيسية */
        .email-container {
            max-width: 550px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* الهيدر */
        .email-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 35px 30px;
            text-align: center;
            position: relative;
        }

        .logo-icon {
            width: 65px;
            height: 65px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-icon i {
            font-size: 28px;
            color: white;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: white;
        }

        .email-header p {
            margin: 8px 0 0;
            font-size: 13px;
            opacity: 0.9;
            color: white;
        }

        /* المحتوى */
        .email-content {
            padding: 35px 30px;
            text-align: center;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .message {
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 14px;
        }

        /* رمز التحقق */
        .code-wrapper {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }

        .code-label {
            font-size: 12px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
        }

        .code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 8px;
            background: white;
            padding: 18px 20px;
            border-radius: 16px;
            display: inline-block;
            color: var(--primary);
            font-family: monospace;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
        }

        /* معلومات إضافية */
        .info-box {
            background: #f0f4ff;
            border-radius: 16px;
            padding: 18px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border-right: 3px solid var(--primary);
        }

        .info-box i {
            font-size: 22px;
            color: var(--primary);
        }

        .info-box p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .info-box strong {
            color: var(--primary-dark);
        }

        .warning-text {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* الفوتر */
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            margin: 0;
            font-size: 12px;
            color: #9ca3af;
        }

        .footer-links {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-size: 11px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* استجابة للهواتف */
        @media (max-width: 576px) {
            body {
                padding: 16px;
            }

            .email-header {
                padding: 25px 20px;
            }

            .email-header h1 {
                font-size: 20px;
            }

            .email-content {
                padding: 25px 20px;
            }

            .code {
                font-size: 32px;
                letter-spacing: 4px;
                padding: 12px 16px;
            }

            .greeting {
                font-size: 16px;
            }

            .message {
                font-size: 13px;
            }

            .info-box {
                padding: 14px;
            }

            .info-box i {
                font-size: 18px;
            }

            .info-box p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- الهيدر -->
        <div class="email-header">
            <div class="logo-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h1>دليلي الذكي</h1>
            <p>منصة الخدمات الحكومية الموحدة</p>
        </div>

        <!-- المحتوى -->
        <div class="email-content">
            <div class="greeting">
                <i class="fas fa-envelope-open-text" style="color: var(--primary); margin-left: 8px;"></i>
                مرحباً بك،
            </div>

            <p class="message">
                لإكمال عملية التسجيل في <strong>دليلي الذكي</strong>،
                يرجى استخدام رمز التحقق التالي:
            </p>

            <!-- رمز التحقق -->
            <div class="code-wrapper">
                <div class="code-label">
                    <i class="fas fa-key"></i> رمز التحقق
                </div>
                <div class="code">{{ $code }}</div>
            </div>

            <!-- معلومات إضافية -->
            <div class="info-box">
                <i class="fas fa-clock"></i>
                <p>هذا الرمز صالح لمدة <strong>10 دقائق</strong> فقط</p>
            </div>

            <div class="warning-text">
                <i class="fas fa-shield-alt"></i>
                <span>إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.</span>
            </div>
        </div>

        <!-- الفوتر -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} دليلي الذكي. جميع الحقوق محفوظة.</p>
            <div class="footer-links">
                <a href="#">عن النظام</a>
                <a href="#">المساعدة</a>
                <a href="#">سياسة الخصوصية</a>
            </div>
        </div>
    </div>

    <!-- إضافة أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</body>
</html>
