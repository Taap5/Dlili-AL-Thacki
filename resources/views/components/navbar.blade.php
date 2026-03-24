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
                <div class="dropdown">
                    <button class="btn btn-link top-link dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" style="color: white; text-decoration: none;">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->user_name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fas fa-id-card me-2"></i>الملف الشخصي
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('favorites') }}">
                            <i class="fas fa-heart me-2"></i>المفضلة
                        </a></li>
                        @if(Auth::user()->hasRole('admin'))
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                            </a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
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
