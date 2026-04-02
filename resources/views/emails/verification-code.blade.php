<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق - دليلي الذكي</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 550px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .email-content {
            padding: 30px;
            text-align: center;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 6px;
            background: #f0f2ff;
            padding: 15px;
            border-radius: 12px;
            display: inline-block;
            margin: 20px 0;
            color: #2f3e9e;
            font-family: monospace;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>دليلي الذكي</h2>
            <p>منصة الخدمات الحكومية الموحدة</p>
        </div>
        <div class="email-content">
            <p>مرحباً،</p>
            <p>لتأكيد تغيير بريدك الإلكتروني، استخدم رمز التحقق التالي:</p>
            <div class="code">{{ $code }}</div>
            <p>هذا الرمز صالح لمدة 10 دقائق.</p>
            <p>إذا لم تقم بطلب هذا التغيير، يرجى تجاهل هذه الرسالة.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} دليلي الذكي. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
