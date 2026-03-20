<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - دليلي الذكي</title>

    <!-- Cairo Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS الخاص بالمصادقة -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">

    <style>
        /* تأثيرات إضافية للتسجيل */
        .register-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }

        .register-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            width: 100%;
            height: 2px;
            background: var(--border-color);
            z-index: 0;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-light);
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }

        .step.active {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
            transform: scale(1.1);
        }

        .step.completed {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .step-label {
            position: absolute;
            bottom: -25px;
            right: 50%;
            transform: translateX(50%);
            font-size: 12px;
            color: var(--text-light);
            white-space: nowrap;
        }

        .step.active .step-label {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 6px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #dc3545;
            transition: var(--transition);
        }

        .strength-text {
            font-size: 12px;
            color: var(--text-light);
        }

        .strength-text.weak { color: #dc3545; }
        .strength-text.medium { color: #ffc107; }
        .strength-text.strong { color: #28a745; }

        .requirements-list {
            margin-top: 10px;
            padding-right: 20px;
        }

        .requirement-item {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .requirement-item.valid {
            color: #28a745;
        }

        .requirement-item i {
            font-size: 10px;
        }
    </style>
</head>
<body class="auth-page">
    <!-- العناصر العائمة -->
    <div class="floating-element" style="width: 120px; height: 120px; top: 5%; left: 8%; background: rgba(74, 91, 201, 0.1);"></div>
    <div class="floating-element" style="width: 70px; height: 70px; bottom: 20%; right: 12%; background: rgba(47, 62, 158, 0.1); animation-delay: 1s;"></div>

    <div class="auth-container">
        <!-- القسم الأيسر (للشاشات الكبيرة) -->
        <div class="auth-left">
            <!-- النجمة المتحركة -->
            <div class="star-field"></div>

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
                <p class="auth-logo-subtext">انضم إلى مجتمعنا الحكومي الرقمي</p>
            </div>

            <div class="auth-welcome">
                <h2 class="auth-welcome-title">انضم إلينا اليوم!</h2>
                <p class="auth-welcome-text">أنشئ حسابك واستمتع بتجربة حكومية رقمية متكاملة وسهلة</p>
            </div>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="fas fa-rocket"></i>
                    <span>إنجاز معاملاتك الحكومية في دقائق</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-bell"></i>
                    <span>متابعة مستمرة لجميع معاملاتك</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-file-contract"></i>
                    <span>حفظ وتوثيق جميع المستندات إلكترونياً</span>
                </div>
                <div class="auth-feature-item">
                    <i class="fas fa-star"></i>
                    <span>مزايا حصرية للأعضاء المسجلين</span>
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
                <p class="auth-mobile-logo-subtext">انضم إلى مجتمعنا</p>
            </a>

            <!-- رأس النموذج -->
            <div class="auth-form-header">
                <h2 class="auth-form-title">إنشاء حساب جديد</h2>
                <p class="auth-form-subtitle">املأ النموذج أدناه لإنشاء حسابك الشخصي</p>
            </div>

            <!-- خطوات التسجيل (للجوال فقط) -->
            <div class="register-steps d-md-none">
                <div class="step active">
                    <span>1</span>
                    <div class="step-label">البيانات</div>
                </div>
                <div class="step">
                    <span>2</span>
                    <div class="step-label">الحساب</div>
                </div>
                <div class="step">
                    <span>3</span>
                    <div class="step-label">التأكيد</div>
                </div>
            </div>

            <!-- نموذج التسجيل -->
            <form class="auth-form" id="registerForm">
                <!-- تنبيه معلوماتي -->
                <div class="auth-alert info">
                    <i class="fas fa-info-circle"></i>
                    <div class="alert-content">
                        <h4>معلومات مهمة</h4>
                        <p>الرجاء استخدام معلومات صحيحة لضمان تفعيل حسابك</p>
                    </div>
                </div>

                <!-- الاسم الكامل -->
                <div class="form-group">
                    <label for="fullName" class="form-label required">الاسم الكامل</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text"
                               id="fullName"
                               class="form-control"
                               placeholder="أحمد محمد العتيبي"
                               required>
                    </div>
                </div>

                <!-- رقم الهوية -->
                <div class="form-group">
                    <label for="nationalId" class="form-label required">رقم الهوية الوطنية</label>
                    <div class="input-group">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text"
                               id="nationalId"
                               class="form-control"
                               placeholder="10XXXXXXXXXX"
                               maxlength="10"
                               required>
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
                </div>

                <!-- رقم الجوال -->
                <div class="form-group">
                    <label for="phone" class="form-label required">رقم الجوال</label>
                    <div class="input-group">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel"
                               id="phone"
                               class="form-control"
                               placeholder="05XXXXXXXX"
                               required>
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
                               placeholder="أنشئ كلمة مرور قوية"
                               required>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <!-- مؤشر قوة كلمة المرور -->
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">كلمة المرور ضعيفة</div>
                    </div>

                    <!-- متطلبات كلمة المرور -->
                    <div class="requirements-list">
                        <div class="requirement-item" id="reqLength">
                            <i class="fas fa-times"></i>
                            <span>8 أحرف على الأقل</span>
                        </div>
                        <div class="requirement-item" id="reqUpper">
                            <i class="fas fa-times"></i>
                            <span>حرف كبير على الأقل</span>
                        </div>
                        <div class="requirement-item" id="reqLower">
                            <i class="fas fa-times"></i>
                            <span>حرف صغير على الأقل</span>
                        </div>
                        <div class="requirement-item" id="reqNumber">
                            <i class="fas fa-times"></i>
                            <span>رقم على الأقل</span>
                        </div>
                        <div class="requirement-item" id="reqSpecial">
                            <i class="fas fa-times"></i>
                            <span>رمز خاص على الأقل</span>
                        </div>
                    </div>
                </div>

                <!-- تأكيد كلمة المرور -->
                <div class="form-group">
                    <label for="confirmPassword" class="form-label required">تأكيد كلمة المرور</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password"
                               id="confirmPassword"
                               class="form-control"
                               placeholder="أعد إدخال كلمة المرور"
                               required>
                    </div>
                    <div class="error-message d-none" id="passwordMatchError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>كلمة المرور غير متطابقة</span>
                    </div>
                </div>

                <!-- الموافقة على الشروط -->
                <div class="form-footer">
                    <div class="terms-agreement">
                        <input type="checkbox" id="agreeTerms" required>
                        <label for="agreeTerms">
                            أوافق على
                            <a href="#">شروط الاستخدام</a>
                            و
                            <a href="#">سياسة الخصوصية</a>
                            لمنصة دليلي الذكي
                        </label>
                    </div>
                </div>

                <!-- زر إنشاء الحساب -->
                <button type="submit" class="auth-submit-btn">
                    <span>إنشاء حساب جديد</span>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </button>
            </form>

            <!-- الفاصل -->
            <div class="auth-divider">
                <span>أو أنشئ حساب باستخدام</span>
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
                    <i class="fas fa-shield-alt me-1"></i>
                    نضمن لك خصوصية وأمان بياناتك الشخصية
                </p>
            </div>

            <!-- رابط تسجيل الدخول -->
            <div class="auth-switch">
                <p>لديك حساب بالفعل؟</p>
                <a href="{{ route('login') }}" class="auth-switch-btn">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    تسجيل الدخول
                </a>
            </div>

            <!-- النص في الأسفل -->
            <div class="auth-footer">
                <p>
                    <i class="fas fa-lock me-1"></i>
                    جميع بياناتك محمية ومشفرة وفق أعلى معايير الأمان
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

            // فحص قوة كلمة المرور
            const passwordStrength = {
                weak: { width: '33%', color: '#dc3545', text: 'ضعيفة' },
                medium: { width: '66%', color: '#ffc107', text: 'متوسطة' },
                strong: { width: '100%', color: '#28a745', text: 'قوية' }
            };

            function checkPasswordStrength(password) {
                let score = 0;
                const requirements = {
                    length: password.length >= 8,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };

                // تحديث أيقونات المتطلبات
                Object.keys(requirements).forEach(key => {
                    const element = document.getElementById(`req${key.charAt(0).toUpperCase() + key.slice(1)}`);
                    if (element) {
                        if (requirements[key]) {
                            element.classList.add('valid');
                            element.innerHTML = '<i class="fas fa-check"></i><span>' + element.textContent.split(' ').slice(1).join(' ') + '</span>';
                            score++;
                        } else {
                            element.classList.remove('valid');
                            element.innerHTML = '<i class="fas fa-times"></i><span>' + element.textContent.split(' ').slice(1).join(' ') + '</span>';
                        }
                    }
                });

                // تحديث شريط القوة
                const strengthFill = document.getElementById('strengthFill');
                const strengthText = document.getElementById('strengthText');

                let strength;
                if (score <= 2) {
                    strength = passwordStrength.weak;
                    strengthText.className = 'strength-text weak';
                } else if (score <= 4) {
                    strength = passwordStrength.medium;
                    strengthText.className = 'strength-text medium';
                } else {
                    strength = passwordStrength.strong;
                    strengthText.className = 'strength-text strong';
                }

                strengthFill.style.width = strength.width;
                strengthFill.style.background = strength.color;
                strengthText.textContent = `كلمة المرور ${strength.text}`;
            }

            // فحص تطابق كلمة المرور
            function checkPasswordMatch() {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const errorElement = document.getElementById('passwordMatchError');

                if (confirmPassword && password !== confirmPassword) {
                    errorElement.classList.remove('d-none');
                    return false;
                } else {
                    errorElement.classList.add('d-none');
                    return true;
                }
            }

            // مراقبة تغييرات كلمة المرور
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });

            document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);

            // محاكاة إنشاء الحساب
            const registerForm = document.getElementById('registerForm');
            const submitBtn = registerForm.querySelector('.auth-submit-btn');
            const progressFill = submitBtn.querySelector('.progress-fill');

            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!checkPasswordMatch()) {
                    return;
                }

                // تعطيل الزر وإظهار التقدم
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>جاري إنشاء الحساب...</span><div class="progress-bar"><div class="progress-fill"></div></div>';

                // محاكاة التقدم
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 5;
                    progressFill.style.width = progress + '%';

                    if (progress >= 100) {
                        clearInterval(progressInterval);

                        // محاكاة النجاح
                        setTimeout(() => {
                            submitBtn.innerHTML = '<span><i class="fas fa-check-circle me-2"></i>تم إنشاء الحساب بنجاح</span><div class="progress-bar"><div class="progress-fill"></div></div>';
                            submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';

                            // إعادة التوجيه بعد ثانية
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 1500);
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
                });
            });

            // تنسيق رقم الجوال
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 0 && !value.startsWith('05')) {
                    value = '05' + value.substring(2);
                }
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                this.value = value;
            });

            // تنسيق رقم الهوية
            const nationalIdInput = document.getElementById('nationalId');
            nationalIdInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                this.value = value;
            });
        });
    </script>
</body>
</html>
