<!-- الشريط الجانبي - يظهر من اليمين -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                         class="rounded-circle"
                         style="width: 48px; height: 48px; object-fit: cover;">
                @else
                    <i class="fas fa-user-circle fa-3x text-primary"></i>
                @endif
                <div>
                    <h5 class="mb-0 fw-bold">{{ Auth::user()->user_name }}</h5>
                    <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <button id="closeSidebar" class="btn-close"></button>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile') }}" class="sidebar-link">
                    <i class="fas fa-id-card me-3"></i>
                    <span>الملف الشخصي</span>
                </a>
            </li>
            <li>
                <a href="{{ route('favorites') }}" class="sidebar-link">
                    <i class="fas fa-heart me-3"></i>
                    <span>المفضلة</span>
                </a>
            </li>
            <li>
                <a href="{{ route('my.reviews') }}" class="sidebar-link">
                    <i class="fas fa-star me-3"></i>
                    <span>تقييماتي</span>
                </a>
            </li>
            <li>
                <a href="{{ route('team') }}" class="sidebar-link">
                    <i class="fas fa-users me-3"></i>
                    <span>من نحن</span>
                </a>
            </li>
            @if(Auth::user()->hasRole('admin'))
                <li class="sidebar-divider"></li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link text-danger">
                        <i class="fas fa-shield-alt me-3"></i>
                        <span>لوحة تحكم المسؤول</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
</div>

<style>
    /* الشريط الجانبي */
    .sidebar {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100%;
        background: #fff;
        box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        z-index: 1050;
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
        direction: rtl;
    }

    .sidebar.open {
        right: 0;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        display: none;
    }

    .sidebar-overlay.active {
        display: block;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }

    .sidebar-body {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin: 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 15px;
    }

    .sidebar-link:hover {
        background: #f0f0f0;
        color: var(--primary-blue);
    }

    .sidebar-link i {
        width: 24px;
        text-align: center;
    }

    .sidebar-divider {
        height: 1px;
        background: #eee;
        margin: 10px 0;
    }

    .sidebar-footer {
        padding: 15px 20px;
        border-top: 1px solid #eee;
    }

    .sidebar-logout-btn {
        width: 100%;
        padding: 10px;
        background: none;
        border: none;
        color: #dc3545;
        text-align: center;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.2s;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .sidebar-logout-btn:hover {
        background: #fff0f0;
    }

    /* تحسين للهواتف */
    @media (max-width: 576px) {
        .sidebar {
            width: 280px;
            right: -280px;
        }
    }
</style>
