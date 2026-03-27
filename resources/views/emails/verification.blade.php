<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق - دليلي الذكي</title>
    <style>
        body {
            font-family: 'Cairo', 'Tahoma', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            background: #f0f2ff;
            padding: 15px;
            border-radius: 12px;
            display: inline-block;
            margin: 20px 0;
            color: #2f3e9e;
        }
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>دليلي الذكي</h1>
            <p>تحقق من بريدك الإلكتروني</p>
        </div>
        <div class="content">
            <p>مرحباً بك،</p>
            <p>لإكمال عملية التسجيل، استخدم رمز التحقق التالي:</p>
            <div class="code">{{ $code }}</div>
            <p>هذا الرمز صالح لمدة 10 دقائق.</p>
            <p>إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} دليلي الذكي. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
