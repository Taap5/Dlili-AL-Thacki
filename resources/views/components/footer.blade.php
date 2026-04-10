<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-row">
            <!-- عن المنصة -->
            <div class="footer-col">
                <h5>عن دليلي الذكي</h5>
                <ul>
                    <li><a href="{{ route('about') }}"><i class="fas fa-info-circle"></i> عن المنصة</a></li>
                    <li><a href="{{ route('about') }}#vision"><i class="fas fa-eye"></i> رؤيتنا</a></li>
                    <li><a href="{{ route('about') }}#mission"><i class="fas fa-bullseye"></i> رسالتنا</a></li>
                    <li><a href="{{ route('team') }}"><i class="fas fa-users"></i> فريق العمل</a></li>
                </ul>
            </div>

            <!-- روابط سريعة -->
  <!-- روابط سريعة -->
<div class="footer-col">
    <h5>روابط سريعة</h5>
    <ul>
        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="{{ route('services.index') }}"><i class="fas fa-concierge-bell"></i> الخدمات</a></li>
        <li><a href="{{ route('governments.index') }}"><i class="fas fa-building"></i> الجهات الحكومية</a></li>
        <li><a href="{{ route('help') }}"><i class="fas fa-question-circle"></i> المساعدة</a></li>
        <li><a href="{{ route('offers.index') }}"><i class="fas fa-gift"></i> العروض الخاصة</a></li>
    </ul>
</div>

            <!-- خدمات المستخدم -->
            <div class="footer-col">
                <h5>خدمات المستخدم</h5>
                <ul>
                    @auth
                        <li><a href="{{ route('profile') }}"><i class="fas fa-user-circle"></i> الملف الشخصي</a></li>
                        <li><a href="{{ route('favorites') }}"><i class="fas fa-heart"></i> المفضلة</a></li>
                        <li><a href="{{ route('my.reviews') }}"><i class="fas fa-star"></i> تقييماتي</a></li>
                    @else
                        <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                        <li><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
                    @endauth
                </ul>
            </div>

            <!-- تواصل معنا -->
            <div class="footer-col">
                <h5>تواصل معنا</h5>
                <ul class="contact-list">
                    <li><i class="fas fa-envelope"></i> <span>info@dalili.com</span></li>
                    <li><i class="fas fa-phone-alt"></i> <span>+967 1 234 567</span></li>
                    <li><i class="fas fa-map-marker-alt"></i> <span>اليمن - صنعاء</span></li>
                </ul>
                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <p>© {{ date('Y') }} <strong>دليلي الذكي</strong>. جميع الحقوق محفوظة</p>
            <p class="small-text">منصة الخدمات الحكومية الموحدة</p>
        </div>
    </div>
</footer>

<style>
    /* ===== تذييل الصفحة ===== */
    .site-footer {
        background: linear-gradient(135deg, #1a1f2e 0%, #2a2f3e 100%);
        color: #fff;
        font-family: "Cairo", sans-serif;
        width: 100%;
        padding: 50px 0 20px;
        margin-top: 60px;
        position: relative;
        clear: both;
    }

    .footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 30px;
        margin-bottom: 40px;
    }

    .footer-col {
        flex: 1;
        min-width: 180px;
    }

    .footer-col h5 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 12px;
        color: #fff;
    }

    .footer-col h5::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 45px;
        height: 3px;
        background: linear-gradient(90deg, #5a6fc9, #2f3e9e);
        border-radius: 3px;
    }

    .footer-col ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-col ul li {
        margin-bottom: 12px;
    }

    .footer-col ul li a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .footer-col ul li a:hover {
        color: #5a6fc9;
        transform: translateX(-5px);
    }

    .footer-col ul li a i {
        width: 20px;
        font-size: 14px;
        color: #5a6fc9;
    }

    .contact-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.85rem;
        margin-bottom: 12px;
    }

    .contact-list li i {
        width: 20px;
        font-size: 14px;
        color: #5a6fc9;
    }

    .social-links {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .social-icon {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s;
        font-size: 16px;
    }

    .social-icon:hover {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        color: white;
        transform: translateY(-3px);
    }

    .footer-copyright {
        text-align: center;
        padding-top: 25px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .footer-copyright p {
        margin: 0;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .footer-copyright strong {
        color: #5a6fc9;
        font-weight: 600;
    }

    .small-text {
        font-size: 0.7rem;
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.4);
    }

    /* استجابة للشاشات المتوسطة */
    @media (max-width: 992px) {
        .footer-row {
            gap: 40px;
        }

        .footer-col {
            min-width: 200px;
        }
    }

    /* استجابة للشاشات الصغيرة */
    @media (max-width: 768px) {
        .site-footer {
            padding: 40px 0 20px;
            margin-top: 40px;
        }

        .footer-inner {
            padding: 0 15px;
        }

        .footer-row {
            gap: 35px;
        }

        .footer-col h5 {
            font-size: 1rem;
        }

        .footer-col ul li a,
        .contact-list li {
            font-size: 0.8rem;
        }
    }

    /* استجابة للهواتف */
    @media (max-width: 576px) {
        .footer-row {
            flex-direction: column;
            gap: 30px;
        }

        .footer-col {
            text-align: center;
        }

        .footer-col h5::after {
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }

        .footer-col ul li a {
            justify-content: center;
        }

        .contact-list li {
            justify-content: center;
        }

        .social-links {
            justify-content: center;
        }
    }
</style>
