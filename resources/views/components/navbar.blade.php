<header class="mobile-header">
    <!-- الشريط العلوي -->
    <div class="top-bar">
        <div class="top-text">
            <i class="fas fa-map-marker-alt me-1"></i>
            ابحث عن خدمتك الحكومية الآن ... بكل سهولة
        </div>
        <div class="top-links">
            <a href="{{ route('about') }}" class="top-link">عن النظام</a>
            <span>|</span>
            <a href="{{ route('help') }}" class="top-link">المساعدة</a>
            <span>|</span>
            <div class="language-switcher">
                <a href="#" class="top-link lang-btn">EN</a>
                <span>|</span>
                <a href="#" class="top-link lang-btn">AR</a>
            </div>
        </div>
    </div>

    <!-- الشريط الرئيسي -->
    <div class="main-bar">
        <div class="logo-area">
            <a href="{{ url('/') }}" class="logo">
                @if (file_exists(public_path('images/logo.png')) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/logo.png'))
                    <img src="{{ asset('images/logo.png') }}"  loading="lazy" alt="Dalili Logo" class="logo-img">
                @else
                    <div class="logo-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                @endif
                <div class="logo-text">
                    <span class="arabic">دليلي <span class="highlight">الذكي</span></span>
                    <span class="english">DALILI AL-THACKI</span>
                </div>
            </a>
        </div>

        <div class="user-area">
            @guest
                <div class="guest-actions">
                    <a href="{{ route('login') }}" class="btn-guest">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>دخول</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-guest btn-guest-primary">
                        <i class="fas fa-user-plus"></i>
                        <span>تسجيل</span>
                    </a>
                </div>
            @else
                <button class="user-menu-btn" id="sidebarToggle">
                    @if (Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle" loading="lazy"
                            style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <span class="user-icon-placeholder">
                            <i class="fas fa-user"></i>
                        </span>
                    @endif
                    <span class="user-name">{{ Auth::user()->user_name }}</span>
                </button>
            @endguest
        </div>
    </div>
</header>

<style>
    /* ===== الهيدر الجديد - متناسق مع التصميم ===== */
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --secondary: #ffc107;
    }

    .mobile-header {
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    /* ===== الشريط العلوي ===== */
    .top-bar {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, #171e3a 100%);
        color: #fff;
        padding: 8px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
    }

    .top-text {
        font-size: 12px;
        opacity: 0.9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 50%;
    }

    .top-text i {
        font-size: 11px;
        margin-left: 4px;
    }

    .top-links {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
    }

    .top-link {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: all 0.2s;
    }

    .top-link:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .top-links span {
        color: rgba(255, 255, 255, 0.3);
    }

    .language-switcher {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lang-btn {
        font-weight: 500;
    }

    /* ===== الشريط الرئيسي ===== */
    .main-bar {
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        direction: ltr;
    }

    /* ===== منطقة الشعار ===== */
    .logo-area {
        flex-shrink: 0;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: transform 0.2s;
    }

    .logo:hover {
        transform: scale(1.02);
    }

    .logo-img {
        width: 45px;
        height: 45px;
        object-fit: contain;
    }

    .logo-placeholder {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 10px rgba(47, 62, 158, 0.2);
    }

    .logo-placeholder i {
        font-size: 24px;
    }

    .logo-text {
        line-height: 1.3;
        text-align: right;
    }

    .logo-text .arabic {
        font-weight: 800;
        font-size: 18px;
        color: #1e293b;
        display: block;
    }

    .logo-text .arabic .highlight {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .logo-text .english {
        font-size: 9px;
        color: var(--primary-light);
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    /* ===== منطقة المستخدم ===== */
    .user-area {
        flex-shrink: 0;
    }

    /* أزرار الزائر */
    .guest-actions {
        display: flex;
        gap: 10px;
    }

    .btn-guest {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid transparent;
    }

    .btn-guest i {
        font-size: 13px;
    }

    .btn-guest:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-guest-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: 0 2px 8px rgba(47, 62, 158, 0.2);
    }

    .btn-guest-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
        transform: translateY(-2px);
    }

    /* زر المستخدم المسجل */
    .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, #171e3a 100%);
        border: none;
        padding: 6px 16px 6px 20px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
        direction: rtl;
        box-shadow: 0 2px 8px rgba(47, 62, 158, 0.15);
    }

    .user-menu-btn:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(47, 62, 158, 0.25);
    }

    /* أيقونة المستخدم */
    .user-menu-btn .user-icon-placeholder {
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .user-menu-btn .user-icon-placeholder i {
        font-size: 14px;
        color: white;
    }

    .user-menu-btn img {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .user-name {
        color: white;
        font-size: 13px;
        font-weight: 500;
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===== استجابة للشاشات ===== */
    @media (max-width: 768px) {
        .top-bar {
            padding: 6px 16px;
        }

        .top-text {
            font-size: 10px;
        }

        .top-links {
            font-size: 10px;
            gap: 6px;
        }

        .main-bar {
            padding: 10px 16px;
        }

        .logo-img,
        .logo-placeholder {
            width: 38px;
            height: 38px;
        }

        .logo-placeholder i {
            font-size: 20px;
        }

        .logo-text .arabic {
            font-size: 15px;
        }

        .logo-text .english {
            font-size: 8px;
        }

        .btn-guest {
            padding: 6px 12px;
            font-size: 12px;
        }

        .user-menu-btn {
            padding: 4px 12px 4px 16px;
        }

        .user-name {
            max-width: 80px;
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .top-bar {
            padding: 5px 12px;
        }

        .top-text {
            font-size: 9px;
            max-width: 45%;
        }

        .top-text i {
            font-size: 8px;
        }

        .top-links {
            font-size: 9px;
            gap: 5px;
        }

        .language-switcher {
            gap: 4px;
        }

        .main-bar {
            padding: 8px 12px;
        }

        .logo-img,
        .logo-placeholder {
            width: 32px;
            height: 32px;
        }

        .logo-placeholder i {
            font-size: 16px;
        }

        .logo-text .arabic {
            font-size: 13px;
        }

        .logo-text .english {
            font-size: 7px;
        }

        .btn-guest {
            padding: 4px 10px;
            font-size: 11px;
        }

        .btn-guest i {
            font-size: 11px;
        }

        .user-menu-btn {
            padding: 4px 10px 4px 12px;
        }

        .user-menu-btn .user-icon-placeholder {
            width: 24px;
            height: 24px;
        }

        .user-menu-btn .user-icon-placeholder i {
            font-size: 12px;
        }

        .user-menu-btn img {
            width: 24px;
            height: 24px;
        }

        .user-name {
            max-width: 60px;
            font-size: 11px;
        }
    }

    /* هواتف صغيرة جداً */
    @media (max-width: 380px) {
        .btn-guest span {
            display: none;
        }

        .btn-guest i {
            margin: 0;
        }

        .btn-guest {
            padding: 6px 10px;
        }

        .user-name {
            display: none;
        }

        .user-menu-btn {
            padding: 6px 12px;
        }

        .logo-text .english {
            display: none;
        }

        .logo-text .arabic {
            font-size: 14px;
        }
    }
</style>
