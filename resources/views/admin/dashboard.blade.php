@extends('layouts.app')

@section('title', 'لوحة تحكم المسؤول - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --danger: #dc3545;
        --warning: #ffc107;
        --success: #10b981;
        --info: #0ea5e9;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --border-light: rgba(47, 62, 158, 0.1);
        --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.12);
    }

    .admin-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .admin-page::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(47, 62, 158, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: floatBg 25s infinite ease-in-out;
    }

    .admin-page::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(90, 111, 201, 0.04) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: floatBg 20s infinite ease-in-out reverse;
    }

    @keyframes floatBg {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
        z-index: 2;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        margin-bottom: 1.5rem;
    }

    .breadcrumb-custom a {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.85rem;
    }

    .breadcrumb-custom a:hover {
        text-decoration: underline;
    }

    .breadcrumb-custom .separator {
        color: var(--text-muted);
        margin: 0 0.5rem;
    }

    .breadcrumb-custom .current {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    /* صفحة العنوان */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.25rem;
    }

    .page-title i {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* بطاقات الإحصائيات */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: var(--card-shadow);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .stat-card:hover .stat-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .stat-card:hover .stat-icon i {
        color: white;
    }

    .stat-icon i {
        font-size: 1.6rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* روابط الإدارة السريعة */
    .quick-links {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        position: relative;
        padding-right: 1rem;
    }

    .section-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.2rem;
    }

    .link-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
        display: block;
    }

    .link-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: var(--card-shadow);
    }

    .link-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .link-card:hover .link-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .link-card:hover .link-icon i {
        color: white;
    }

    .link-icon i {
        font-size: 1.5rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .link-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.3rem;
    }

    .link-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* جداول البيانات */
    .data-table-wrapper {
        background: white;
        border-radius: 20px;
        overflow-x: auto;
        border: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .data-table th {
        padding: 0.8rem 1rem;
        text-align: center;
        background: #fafbfc;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 0.8rem 1rem;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background: #f8fafc;
    }

    .badge-admin {
        background: linear-gradient(135deg, var(--danger), #c82333);
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-user {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .view-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.2s;
    }

    .view-link:hover {
        color: var(--primary-light);
        gap: 0.5rem;
    }

    .empty-data {
        text-align: center;
        padding: 2rem;
        color: var(--text-muted);
    }

    .empty-data i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-container {
            padding: 0 1rem;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .links-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .link-card {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page">
    <div class="admin-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span class="separator">/</span>
            <span class="current">لوحة تحكم المسؤول</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-shield-alt"></i> لوحة تحكم المسؤول
            </h1>
            <p class="page-subtitle">مرحباً بك في لوحة التحكم، يمكنك من هنا إدارة جميع أقسام الموقع</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="stat-number">{{ number_format($stats['governments_count']) }}</div>
                <div class="stat-label">الجهات الحكومية</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                </div>
                <div class="stat-number">{{ number_format($stats['services_count']) }}</div>
                <div class="stat-label">الخدمات المتوفرة</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number">{{ number_format($stats['users_count']) }}</div>
                <div class="stat-label">المستخدمين المسجلين</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="stat-number">{{ number_format($stats['reviews_count']) }}</div>
                <div class="stat-label">التقييمات</div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-cog me-2"></i> الإدارة السريعة
                </div>
            </div>
            <div class="links-grid">
                <a href="{{ route('admin.governments') }}" class="link-card">
                    <div class="link-icon"><i class="fas fa-building"></i></div>
                    <div class="link-title">إدارة الجهات</div>
                    <div class="link-desc">إضافة، تعديل، حذف الجهات الحكومية</div>
                </a>
                <a href="{{ route('admin.services') }}" class="link-card">
                    <div class="link-icon"><i class="fas fa-concierge-bell"></i></div>
                    <div class="link-title">إدارة الخدمات</div>
                    <div class="link-desc">إضافة، تعديل، حذف الخدمات</div>
                </a>
                <a href="{{ route('admin.users') }}" class="link-card">
                    <div class="link-icon"><i class="fas fa-users"></i></div>
                    <div class="link-title">إدارة المستخدمين</div>
                    <div class="link-desc">عرض وإدارة المستخدمين والأدوار</div>
                </a>
                <a href="{{ route('admin.reviews') }}" class="link-card">
                    <div class="link-icon"><i class="fas fa-star"></i></div>
                    <div class="link-title">إدارة التقييمات</div>
                    <div class="link-desc">عرض وإدارة تقييمات المستخدمين</div>
                </a>
            </div>
        </div>

        <!-- Latest Users -->
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-user-plus"></i> آخر المستخدمين
            </div>
            <a href="{{ route('admin.users') }}" class="view-link">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_users'] as $user)
                        <tr>
                            <td>{{ $user->user_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="{{ $user->hasRole('admin') ? 'badge-admin' : 'badge-user' }}">
                                    {{ $user->hasRole('admin') ? 'مسؤول' : 'مستخدم' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-data">
                                <i class="fas fa-users"></i>
                                <p>لا توجد بيانات</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Latest Reviews -->
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-star"></i> آخر التقييمات
            </div>
            <a href="{{ route('admin.reviews') }}" class="view-link">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الجهة</th>
                        <th>التقييم</th>
                        <th>التعليق</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_reviews'] as $review)
                        <tr>
                            <td>{{ $review->user->user_name ?? 'مستخدم محذوف' }}</td>
                            <td>
                                <a href="{{ route('governments.show', $review->government->id) }}" class="view-link" style="color: var(--primary);">
                                    {{ $review->government->name }}
                                </a>
                            </td>
                            <td>
                                <div class="review-stars" style="color: var(--warning);">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span style="background: #f0f4ff; padding: 0.1rem 0.4rem; border-radius: 20px; font-size: 0.7rem; color: var(--primary); margin-right: 0.3rem;">{{ $review->rating }}</span>
                                </div>
                            </td>
                            <td>
                                @if($review->comment)
                                    <div style="max-width: 200px; font-size: 0.75rem; color: var(--text-muted);">
                                        {{ Str::limit($review->comment, 40) }}
                                    </div>
                                @else
                                    <span class="text-muted">لا يوجد تعليق</span>
                                @endif
                            </td>
                            <td style="font-size: 0.7rem; color: #9ca3af;">{{ $review->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-data">
                                <i class="fas fa-star"></i>
                                <p>لا توجد تقييمات حتى الآن</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        @if(session('success'))
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        @endif

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('lastListPage', window.location.href);
            });
        });

        if (sessionStorage.getItem('lastListPage') && window.location.href === sessionStorage.getItem('lastListPage')) {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            sessionStorage.removeItem('lastListPage');
        }
    })();
</script>
@endpush
@endsection
