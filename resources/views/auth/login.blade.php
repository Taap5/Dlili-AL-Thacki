<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - دليلي الذكي</title>

    <!-- Cairo Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS الخاص بالمصادقة -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">

    <style>
        /* تأثيرات إضافية */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-blue);
            border-radius: 50%;
            opacity: 0.3;
        }
    </style>
</head>
<body class="auth-page">
    <!-- العناصر العائمة -->
    <div class="floating-element" style="width: 100px; height: 100px; top: 10%; right: 5%; animation-delay: 0s;"></div>
    <div class="floating-element" style="width: 60px; height: 60px; bottom: 15%; left: 10%; animation-delay: 2s;"></div>
    <div class="floating-element" style="width: 80px; height: 80px; top: 60%; right: 15%; animation-delay: 4s;"></div>

    <!-- الجسيمات -->
    <div class="particle" style="top: 20%; right: 20%;"></div>
    <div class="particle" style="top: 40%; left: 15%;"></div>
    <div class="particle" style="bottom: 30%; right: 10%;"></div>
    <div class="particle" style="bottom: 10%; left: 20%;"></div>

    <div class="auth-container">
        <!-- القسم الأيسر (للشاشات الكبيرة) -->
        <div class="auth-left">
            <!-- النجمة المتحركة -->
            <div class="star-field">
                <!-- سيتم إنشاء النجوم بالجافاسكريبت -->
            </div>

            <!-- الشعار والمحتوى -->
            <div class="auth-logo">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="دليلي الذكي" class="auth-logo-img">
                @else
                    <div class="auth-logo-placeholder">
                        <span>د</span>
                    </div>
                @endif
                <h1 class="auth-logo-text">دليلي الذكي</h1>
                <p class="auth-logo-subtext">منصة الخدمات الحكومية الموحدة</p>
            </div>

            <div class="auth-welcome">
                <h2 class="auth-welcome-title">مرحباً بعودتك!</h2>
                <p class="auth-welcome-text">سجل دخولك الآن للوصول إلى جميع خدماتنا الحكومية المتكاملة</p>
            </div>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="fas fa-shield-check"></i>
                    <span>حماية وأمان تام لبياناتك</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>وصول سريع للخدمات الحكومية</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-headset"></i>
                    <span>دعم فني متكامل على مدار الساعة</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>تتبع سجل معاملاتك بسهولة</span>
                </div>
            </div>
        </div>

        <!-- القسم الأيمن (النموذج) -->
        <div class="auth-right">
            <!-- شعار الموبايل -->
            <a href="{{ url('/') }}" class="auth-mobile-logo">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="دليلي الذكي" class="auth-mobile-logo-img">
                @else
                    <div class="auth-mobile-logo-placeholder">
                        <span>د</span>
                    </div>
                @endif
                <h2 class="auth-mobile-logo-text">دليلي الذكي</h2>
                <p class="auth-mobile-logo-subtext">منصة الخدمات الحكومية</p>
            </a>

            <!-- رأس النموذج -->
            <div class="auth-form-header">
                <h2 class="auth-form-title">تسجيل الدخول</h2>
                <p class="auth-form-subtitle">أدخل بيانات حسابك للوصول إلى خدماتنا</p>
            </div>

            <!-- نموذج تسجيل الدخول -->
            <form class="auth-form" id="loginForm">
                <!-- تنبيه معلوماتي -->
                <div class="auth-alert info">
                    <i class="fas fa-info-circle"></i>
                    <div class="alert-content">
                        <h4>مرحباً بعودتك!</h4>
                        <p>سجل دخولك للوصول إلى جميع الخدمات الحكومية</p>
                    </div>
                </div>

                <!-- البريد الإلكتروني -->
                <div class="form-group">
                    <label for="email" class="form-label required">البريد الإلكتروني</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email"
                               id="email"
                               class="form-control"
                               placeholder="example@email.com"
                               required>
                    </div>
                    <div class="error-message d-none">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>الرجاء إدخال بريد إلكتروني صحيح</span>
                    </div>
                </div>

                <!-- كلمة المرور -->
                <div class="form-group">
                    <label for="password" class="form-label required">كلمة المرور</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password"
                               id="password"
                               class="form-control"
                               placeholder="أدخل كلمة المرور"
                               required>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message d-none">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>كلمة المرور يجب أن تكون 6 أحرف على الأقل</span>
                    </div>
                </div>

                <!-- تذكرني ونسيت كلمة المرور -->
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe">تذكرني</label>
                    </div>
                    <a href="#" class="forgot-password">
                        <i class="fas fa-key me-1"></i>
                        نسيت كلمة المرور؟
                    </a>
                </div>

                <!-- زر تسجيل الدخول -->
                <button type="submit" class="auth-submit-btn">
                    <span>تسجيل الدخول</span>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </button>
            </form>

            <!-- الفاصل -->
            <div class="auth-divider">
                <span>أو سجل الدخول باستخدام</span>
            </div>

            <!-- تسجيل الدخول بالشبكات الاجتماعية -->
            <div class="social-login">
                <div class="social-buttons">
                    <a href="#" class="social-btn google">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </a>
                    <a href="#" class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="social-btn apple">
                        <i class="fab fa-apple"></i>
                        <span>Apple</span>
                    </a>
                </div>
                <p class="social-note">
                    <i class="fas fa-lock me-1"></i>
                    نضمن لك خصوصية وأمان بياناتك عند استخدام خدمات الطرف الثالث
                </p>
            </div>

            <!-- رابط إنشاء حساب جديد -->
            <div class="auth-switch">
                <p>ليس لديك حساب بعد؟</p>
                <a href="{{ route('register') }}" class="auth-switch-btn">
                    <i class="fas fa-user-plus me-2"></i>
                    إنشاء حساب جديد
                </a>
            </div>

            <!-- النص في الأسفل -->
            <div class="auth-footer">
                <p>
                    بالدخول إلى حسابك، فإنك توافق على
                    <a href="#">شروط الخدمة</a>
                    و
                    <a href="#">سياسة الخصوصية</a>
                </p>
                <p>
                    <a href="{{ url('/') }}">
                        <i class="fas fa-arrow-right me-1"></i>
                        العودة إلى الصفحة الرئيسية
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // إنشاء النجوم
            const starField = document.querySelector('.star-field');
            for (let i = 0; i < 50; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.width = Math.random() * 3 + 1 + 'px';
                star.style.height = star.style.width;
                star.style.right = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 3 + 's';
                starField.appendChild(star);
            }

            // تبديل إظهار/إخفاء كلمة المرور
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');

            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // محاكاة تسجيل الدخول
            const loginForm = document.getElementById('loginForm');
            const submitBtn = loginForm.querySelector('.auth-submit-btn');
            const progressFill = submitBtn.querySelector('.progress-fill');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // تعطيل الزر وإظهار التقدم
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>جاري تسجيل الدخول...</span><div class="progress-bar"><div class="progress-fill"></div></div>';

                // محاكاة التقدم
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 10;
                    progressFill.style.width = progress + '%';

                    if (progress >= 100) {
                        clearInterval(progressInterval);

                        // محاكاة النجاح
                        setTimeout(() => {
                            submitBtn.innerHTML = '<span><i class="fas fa-check-circle me-2"></i>تم تسجيل الدخول بنجاح</span><div class="progress-bar"><div class="progress-fill"></div></div>';
                            submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';

                            // إعادة التوجيه بعد ثانية
                            setTimeout(() => {
                                window.location.href = '/';
                            }, 1000);
                        }, 500);
                    }
                }, 100);
            });

            // تأثيرات الإدخال
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.value) {
                        this.parentElement.classList.add('has-value');
                    } else {
                        this.parentElement.classList.remove('has-value');
                    }
                });
            });
        });
    </script>
</body>
</html>
