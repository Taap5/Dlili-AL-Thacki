<header class="mobile-header">

    <!-- الشريط العلوي -->
    <div class="top-bar d-flex justify-content-between align-items-center">
        <div class="top-text">
            ابحث عن خدمتك الحكومية الآن ... بكل سهولة
        </div>

<div class="top-links d-flex align-items-center gap-2">
    <a href="{{ route('about') }}" class="top-link">عن النظام</a>
    <span>|</span>

    @guest
        <a href="{{ route('login') }}" class="top-link">تسجيل دخول</a>
        <a href="{{ route('register') }}" class="top-link">إنشاء حساب</a>
    @else
        <div class="user-info d-flex align-items-center gap-2">
            <img src="{{ Auth::user()->profile_photo
                        ? asset('storage/' . Auth::user()->profile_photo)
                        : asset('images/default-profile.png') }}"
                 alt="User Photo" class="rounded-circle" width="32" height="32">
            <a href="{{ route('dashboard') }}" class="top-link">
                {{ Auth::user()->user_name }}
            </a>
        </div>
    @endguest
</div>

    </div>

    <!-- الشريط الرئيسي -->
    <div class="main-bar d-flex justify-content-between align-items-center">

        <!-- الشعار -->
        <a href="{{ url('/') }}" class="logo d-flex align-items-center gap-2">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Dalili Logo" class="logo-img">
            @else
                <div class="logo-placeholder">
                    D
                </div>
            @endif
            <div class="logo-text">
                <span class="arabic">دليلي الذكي</span>
                <span class="english">DALILI AL-THACKI</span>
            </div>
        </a>

        <!-- اللغة + التصنيف -->
        <div class="actions d-flex align-items-center">
            <div class="language">
                <a href="#" class="lang-btn">EN</a> |
                <a href="#" class="lang-btn">AR</a>
            </div>
        </div>

    </div>

</header>
