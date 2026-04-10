<!-- الشريط الجانبي - يظهر من اليمين -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="d-flex justify-content-between align-items-center">
            <button id="closeSidebar" class="btn-close-custom">
                <i class="fas fa-times"></i>
            </button>
            <div class="user-info">
                @if(Auth::user()->profile_photo)
                    <div class="user-avatar">
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                             alt="{{ Auth::user()->user_name }}">
                    </div>
                @else
                    <div class="user-avatar user-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div class="user-details">
                    <h5 class="user-name">{{ Auth::user()->user_name }}</h5>
                    <p class="user-email">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <div class="sidebar-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="sidebar-link-text">لوحة التحكم</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile') }}" class="sidebar-link">
                    <div class="sidebar-link-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <span class="sidebar-link-text">الملف الشخصي</span>
                </a>
            </li>
            <li>
                <a href="{{ route('favorites') }}" class="sidebar-link">
                    <div class="sidebar-link-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <span class="sidebar-link-text">المفضلة</span>
                </a>
            </li>
            <li>
                <a href="{{ route('my.reviews') }}" class="sidebar-link">
                    <div class="sidebar-link-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="sidebar-link-text">تقييماتي</span>
                </a>
            </li>
            <li>
                <a href="{{ route('team') }}" class="sidebar-link">
                    <div class="sidebar-link-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="sidebar-link-text">من نحن</span>
                </a>
            </li>

            @if(Auth::user()->hasRole('admin'))
                <li class="sidebar-divider"></li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link sidebar-link-admin">
                        <div class="sidebar-link-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="sidebar-link-text">لوحة تحكم المسؤول</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</div>

<style>
    /* ===== الشريط الجانبي المحسّن ===== */
    :root {
        --primary-blue: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
    }

    .sidebar {
        position: fixed;
        top: 0;
        right: -380px;
        width: 360px;
        height: 100%;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        direction: rtl;
    }

    .sidebar.open {
        right: 0;
    }

    /* الطبقة الخلفية */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1040;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    /* ===== رأس الشريط ===== */
    .sidebar-header {
        padding: 30px 24px 24px;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, #171e3a 100%);
        position: relative;
        overflow: hidden;
    }

    .sidebar-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .btn-close-custom {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .btn-close-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .btn-close-custom i {
        font-size: 18px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 16px;
    }

    .user-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar-placeholder {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar-placeholder i {
        font-size: 32px;
        color: white;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin: 0 0 4px 0;
    }

    .user-email {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
    }

    /* ===== جسم الشريط ===== */
    .sidebar-body {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }

    .sidebar-body::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-body::-webkit-scrollbar-track {
        background: #f0f0f0;
    }

    .sidebar-body::-webkit-scrollbar-thumb {
        background: var(--primary-blue);
        border-radius: 4px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin: 0;
    }

    /* روابط القائمة المحسّنة */
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 24px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 15px;
        font-weight: 500;
        position: relative;
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 70%;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
        border-radius: 0 8px 8px 0;
        transition: width 0.2s ease;
    }

    .sidebar-link:hover {
        background: #f0f4ff;
        color: var(--primary-blue);
    }

    .sidebar-link:hover::before {
        width: 4px;
    }

    .sidebar-link-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4ff;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .sidebar-link-icon i {
        font-size: 16px;
        color: var(--primary-blue);
        transition: all 0.2s ease;
    }

    .sidebar-link:hover .sidebar-link-icon {
        background: var(--primary-blue);
    }

    .sidebar-link:hover .sidebar-link-icon i {
        color: white;
    }

    .sidebar-link-text {
        flex: 1;
    }

    /* رابط لوحة تحكم المسؤول */
    .sidebar-link-admin .sidebar-link-icon {
        background: #fff0f0;
    }

    .sidebar-link-admin .sidebar-link-icon i {
        color: #dc3545;
    }

    .sidebar-link-admin:hover .sidebar-link-icon {
        background: #dc3545;
    }

    .sidebar-link-admin:hover .sidebar-link-icon i {
        color: white;
    }

    /* الفاصل */
    .sidebar-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 16px 24px;
    }

    /* ===== تذييل الشريط ===== */
    .sidebar-footer {
        padding: 20px 24px 30px;
        border-top: 1px solid #e2e8f0;
    }

    .sidebar-logout-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #fff5f5, #ffffff);
        border: 1px solid #fee2e2;
        border-radius: 12px;
        color: #dc3545;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .sidebar-logout-btn i {
        font-size: 16px;
    }

    .sidebar-logout-btn:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    /* ===== تحسين للهواتف ===== */
    @media (max-width: 576px) {
        .sidebar {
            width: 300px;
            right: -300px;
        }

        .sidebar-header {
            padding: 24px 20px 20px;
        }

        .user-avatar {
            width: 52px;
            height: 52px;
        }

        .user-name {
            font-size: 1rem;
        }

        .sidebar-link {
            padding: 10px 20px;
            gap: 12px;
        }

        .sidebar-link-icon {
            width: 28px;
            height: 28px;
        }

        .sidebar-link-icon i {
            font-size: 14px;
        }

        .sidebar-footer {
            padding: 16px 20px 24px;
        }

        .sidebar-logout-btn {
            padding: 10px;
            font-size: 13px;
        }
    }

    /* ===== تحسين للشاشات المتوسطة ===== */
    @media (min-width: 577px) and (max-width: 768px) {
        .sidebar {
            width: 320px;
            right: -320px;
        }
    }
</style>

<script>
    // JavaScript لفتح وإغلاق الشريط الجانبي
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('closeSidebar');
        const toggleBtn = document.getElementById('sidebarToggle'); // الزر الذي يفتح الشريط

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', openSidebar);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // إغلاق الشريط بالضغط على ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    });
</script>
