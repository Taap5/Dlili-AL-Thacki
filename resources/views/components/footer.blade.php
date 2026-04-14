<footer class="site-footer">
    <div class="footer-container">
        <!-- الصف الأوسط: المحتوى الرئيسي -->
        <div class="footer-main">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" loading="lazy" alt="دليلي الذكي" class="logo-img" onerror="this.src='https://placehold.co/50x50/ffffff/2f3e9e?text=D'">
                    <h3>دليلي <span>الذكي</span></h3>
                </div>
                <p class="brand-description">منصة رائدة تجمع جميع الخدمات الحكومية في مكان واحد، لتوفير الوقت والجهد على المواطنين والمقيمين.</p>
                <div class="trust-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>منصة موثوقة ومعتمدة</span>
                </div>
            </div>

            <div class="footer-links-grid">
                <div class="links-group">
                    <h5>استكشف</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li><a href="{{ route('services.index') }}">الخدمات</a></li>
                        <li><a href="{{ route('governments.index') }}">الجهات الحكومية</a></li>
                        <li><a href="{{ route('offers.index') }}">العروض الخاصة</a></li>
                    </ul>
                </div>
                <div class="links-group">
                    <h5>عن المنصة</h5>
                    <ul>
                        <li><a href="{{ route('about') }}">عن دليلي الذكي</a></li>
                        <li><a href="{{ route('about') }}#vision">رؤيتنا</a></li>
                        <li><a href="{{ route('about') }}#mission">رسالتنا</a></li>
                        <li><a href="{{ route('team') }}">فريق العمل</a></li>
                    </ul>
                </div>
                <div class="links-group">
                    <h5>الدعم</h5>
                    <ul>
                        <li><a href="{{ route('help') }}">المساعدة</a></li>
                        @auth
                            <li><a href="{{ route('profile') }}">الملف الشخصي</a></li>
                            <li><a href="{{ route('favorites') }}">المفضلة</a></li>
                        @else
                            <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
                            <li><a href="{{ route('register') }}">إنشاء حساب</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="footer-newsletter">
                <h5>اشترك في النشرة البريدية</h5>
                <div class="newsletter-form">
                    <input type="email" placeholder="بريدك الإلكتروني">
                    <button><i class="fas fa-paper-plane"></i></button>
                </div>
                <p class="newsletter-note">احصل على آخر التحديثات والعروض الحصرية</p>
                <div class="footer-contact">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>+967 1 234 567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@dalili-smart.com</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-section">
                <span>تابعنا على</span>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="copyright">
                <p>© {{ date('Y') }} <strong>دليلي الذكي</strong>. جميع الحقوق محفوظة</p>
                <p class="location"><i class="fas fa-map-marker-alt"></i> اليمن - صنعاء</p>
            </div>
        </div>
    </div>
</footer>

<style>
    /* ===== الفوتر - بدون إحصائيات ===== */
    :root {
        --primary: #1b2463;
        --primary-light: #414970;
        --primary-dark: #161b41;
    }

    .site-footer {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, #171e3a 100%);
        margin-top: 4rem;
        position: relative;
        overflow: hidden;
    }

    .site-footer::before {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -100px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: floatParticle 25s infinite ease-in-out;
    }

    .site-footer::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.06) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: floatParticle 20s infinite ease-in-out reverse;
    }

    @keyframes floatParticle {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 1.5rem 1.5rem;
        position: relative;
        z-index: 2;
    }

    .footer-main {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-brand {
        flex: 1.5;
        min-width: 220px;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .logo-img {
        width: 45px;
        height: 45px;
        border-top-left-radius: 0px;
border-top-right-radius:20px;
border-bottom-left-radius:0px;
border-bottom-right-radius:20px;
        background: white;
        object-fit: cover;
    }

    .footer-logo h3 {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0;
        color: white;
    }

    .footer-logo h3 span {
        color: white;
        position: relative;
    }

    .footer-logo h3 span::after {
        content: '';
        position: absolute;
        bottom: -4px;
        right: 0;
        width: 100%;
        height: 2px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 2px;
    }

    .brand-description {
        font-size: 0.85rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1rem;
    }

    .trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 0.75rem;
        color: white;
    }

    .footer-links-grid {
        flex: 2;
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .links-group {
        min-width: 120px;
    }

    .links-group h5 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
        position: relative;
        display: inline-block;
    }

    .links-group h5::after {
        content: '';
        position: absolute;
        bottom: -6px;
        right: 0;
        width: 30px;
        height: 2px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 2px;
    }

    .links-group ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .links-group ul li {
        margin-bottom: 10px;
    }

    .links-group ul li a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 0.8rem;
        transition: all 0.2s;
        display: inline-block;
    }

    .links-group ul li a:hover {
        color: white;
        transform: translateX(-3px);
    }

    .footer-newsletter {
        flex: 1.2;
        min-width: 220px;
    }

    .footer-newsletter h5 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }

    .newsletter-form {
        display: flex;
        gap: 8px;
        margin-bottom: 0.5rem;
    }

    .newsletter-form input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.08);
        border-radius: 40px;
        color: white;
        font-size: 0.8rem;
    }

    .newsletter-form input:focus {
        outline: none;
        border-color: white;
        background: rgba(255, 255, 255, 0.12);
    }

    .newsletter-form input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-form button {
        width: 42px;
        height: 42px;
        background: white;
        border: none;
        border-radius: 50%;
        color: var(--primary);
        cursor: pointer;
        transition: all 0.2s;
    }

    .newsletter-form button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .newsletter-note {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 1rem;
    }

    .footer-contact {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .contact-item i {
        width: 25px;
        color: white;
    }

    .footer-bottom {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding-top: 1.5rem;
        margin-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .social-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .social-section span {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .social-icons {
        display: flex;
        gap: 10px;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.2s;
        text-decoration: none;
    }

    .social-icon:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-3px);
    }

    .copyright {
        text-align: left;
    }

    .copyright p {
        margin: 0;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .copyright strong {
        color: white;
    }

    .location {
        margin-top: 4px !important;
        font-size: 0.7rem !important;
    }

    /* استجابة */
    @media (max-width: 768px) {
        .footer-container {
            padding: 2rem 1rem 1rem;
        }

        .footer-main {
            flex-direction: column;
        }

        .footer-links-grid {
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .copyright {
            text-align: center;
        }

        .social-section {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .footer-logo {
            justify-content: center;
        }

        .brand-description {
            text-align: center;
        }

        .trust-badge {
            margin: 0 auto;
        }

        .links-group {
            text-align: center;
            width: 100%;
        }

        .links-group h5::after {
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }

        .footer-newsletter {
            text-align: center;
        }

        .contact-item {
            justify-content: center;
        }
    }
</style>
