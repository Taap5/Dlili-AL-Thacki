<footer class="mobile-footer">
    <div class="container">
        <!-- الروابط الرئيسية -->
        <div class="footer-main">
            <div class="footer-grid">
                <!-- عن المنصة -->
                <div class="footer-col">
                    <h5 class="footer-title">عن دليلي الذكي</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">عن المنصة</a></li>
                        <li><a href="{{ route('about') }}#vision">رؤيتنا</a></li>
                        <li><a href="{{ route('about') }}#mission">رسالتنا</a></li>
                        <li><a href="{{ route('about') }}#team">فريق العمل</a></li>
                    </ul>
                </div>

                <!-- روابط سريعة -->
                <div class="footer-col">
                    <h5 class="footer-title">روابط سريعة</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('services.index') }}">الخدمات</a></li>
                        <li><a href="{{ route('governments.index') }}">الجهات الحكومية</a></li>
                        <li><a href="{{ route('help') }}">المساعدة</a></li>
                    </ul>
                </div>

                <!-- خدمات المستخدم -->
                <div class="footer-col">
                    <h5 class="footer-title">خدمات المستخدم</h5>
                    <ul class="footer-links">
                        @auth
                            <li><a href="{{ route('profile') }}">الملف الشخصي</a></li>
                            <li><a href="{{ route('favorites') }}">المفضلة</a></li>
                            <li><a href="{{ route('my.reviews') }}">تقييماتي</a></li>
                        @else
                            <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
                            <li><a href="{{ route('register') }}">إنشاء حساب</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- تواصل معنا -->
                <div class="footer-col">
                    <h5 class="footer-title">تواصل معنا</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i> info@dalili.com</li>
                        <li><i class="fas fa-phone me-2"></i> +967 1 234 567</li>
                    </ul>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم حقوق النشر -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                <p>© {{ date('Y') }} دليلي الذكي. جميع الحقوق محفوظة</p>
                <p class="small">منصة الخدمات الحكومية الموحدة</p>
            </div>
        </div>
    </div>
</footer>

<style>
    .mobile-footer {
        background-color: #1a1f2e;
        color: #fff;
        font-family: "Cairo", sans-serif;
        margin-top: auto;
        padding: 40px 0 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .footer-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 2px;
        background: #2f3e9e;
        border-radius: 2px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }

    .footer-links a:hover {
        color: #fff;
        transform: translateX(-5px);
    }

    .footer-links i {
        color: #2f3e9e;
        width: 24px;
    }

    .footer-social {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-link {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s;
    }

    .social-link:hover {
        background: #2f3e9e;
        transform: translateY(-3px);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-copyright p {
        margin: 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    .footer-copyright .small {
        font-size: 11px;
        margin-top: 5px;
    }

    /* تحسين للهواتف */
    @media (max-width: 768px) {
        .mobile-footer {
            padding: 30px 0 15px;
        }
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .footer-title {
            font-size: 16px;
        }
        .footer-links li {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .footer-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .footer-title:after {
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }
        .footer-social {
            justify-content: center;
        }
        .footer-links a:hover {
            transform: none;
        }
    }
</style>
