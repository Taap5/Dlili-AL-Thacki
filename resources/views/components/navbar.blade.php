<header class="mobile-header">
    <!-- الشريط العلوي (الأزرق) -->
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

    <!-- الشريط الرئيسي (الأبيض) -->
    <div class="main-bar">
        <div class="logo-area">
            <a href="{{ url('/') }}" class="logo">
                @if (file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Dalili Logo" class="logo-img">
                @else
                    <div class="logo-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                @endif
                <div class="logo-text">
                    <span class="arabic">دليلي الذكي</span>
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
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle"
                            style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endif
                    <span class="user-name">{{ Auth::user()->user_name }}</span>
                </button>
            @endguest
        </div>
    </div>
</header>

<style>
    /* ===== هيدر جديد ===== */
    .mobile-header {
        background: #fff;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    /* الشريط العلوي (الأزرق) */
    .top-bar {
        background: linear-gradient(135deg, #1a1f2e 0%, #2a2f3e 100%);
        color: #fff;
        padding: 8px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
    }

    .top-text {
        font-size: 11px;
        opacity: 0.9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 50%;
    }

    .top-text i {
        font-size: 10px;
        margin-left: 4px;
    }

    .top-links {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
    }

    .top-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.2s;
    }

    .top-link:hover {
        color: #fff;
    }

    .top-links span {
        color: rgba(255, 255, 255, 0.3);
    }

    .language-switcher {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* الشريط الرئيسي (الأبيض) */
    .main-bar {
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        direction: ltr;
    }

    /* منطقة الشعار */
    .logo-area {
        flex-shrink: 0;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .logo-img {
        width: 42px;
        height: 42px;
        object-fit: contain;
    }

    .logo-placeholder {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .logo-placeholder i {
        font-size: 22px;
    }

    .logo-text {
        line-height: 1.3;
        text-align: right;
    }

    .logo-text .arabic {
        font-weight: 700;
        font-size: 16px;
        color: #1a1f2e;
        display: block;
    }

    .logo-text .english {
        font-size: 9px;
        color: #2f3e9e;
        letter-spacing: 0.5px;
    }

    /* منطقة المستخدم */
    .user-area {
        flex-shrink: 0;
    }

    /* أزرار الزائر */
    .guest-actions {
        display: flex;
        gap: 8px;
    }

    .btn-guest {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        background: #f5f5f5;
        color: #555;
    }

    .btn-guest i {
        font-size: 12px;
    }

    .btn-guest:hover {
        background: #e8e8e8;
    }

    .btn-guest-primary {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        color: white;
    }

    .btn-guest-primary:hover {
        background: linear-gradient(135deg, #25327a, #4a5fb0);
        color: white;
    }

    /* زر المستخدم المسجل */
    .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f5f5f5;
        border: none;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 500;
        color: #1a1f2e;
        cursor: pointer;
        transition: all 0.2s;
        direction: rtl;
    }

    .user-menu-btn i {
        font-size: 24px;
        color: #2f3e9e;
    }

    .user-menu-btn img {
        width: 32px;
        height: 32px;
        object-fit: cover;
    }

    .user-menu-btn:hover {
        background: #e8e8e8;
        transform: translateY(-1px);
    }

    .user-name {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* تحسين للهواتف */
    @media (max-width: 576px) {
        .top-bar {
            padding: 6px 12px;
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

        .main-bar {
            padding: 8px 12px;
        }

        .logo-img,
        .logo-placeholder {
            width: 36px;
            height: 36px;
        }

        .logo-placeholder i {
            font-size: 18px;
        }

        .logo-text .arabic {
            font-size: 14px;
        }

        .logo-text .english {
            font-size: 8px;
        }

        .btn-guest {
            padding: 4px 10px;
            font-size: 11px;
        }

        .btn-guest i {
            font-size: 11px;
        }

        .user-menu-btn {
            padding: 4px 10px;
            font-size: 12px;
        }

        .user-menu-btn i {
            font-size: 20px;
        }

        .user-name {
            max-width: 80px;
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
    }
</style>
