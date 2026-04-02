@extends('layouts.app')

@section('title', 'لوحة تحكم المسؤول')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --danger: #dc3545;
        --warning: #ffc107;
        --success: #28a745;
        --info: #17a2b8;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
        --bg-gray: #f8f9fa;
        --border-light: #eef2f6;
    }

    main {
        padding-top: 100px !important;
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* بطاقات الإحصائيات */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 28px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon i {
        font-size: 28px;
        color: var(--primary);
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* روابط الإدارة السريعة */
    .quick-links {
        margin-bottom: 40px;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        position: relative;
        padding-right: 16px;
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
        gap: 20px;
    }

    .link-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid var(--border-light);
        display: block;
    }

    .link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        border-color: rgba(47, 62, 158, 0.2);
    }

    .link-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 12px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .link-card:hover .link-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
    }

    .link-icon i {
        font-size: 24px;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .link-card:hover .link-icon i {
        color: white;
    }

    .link-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .link-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* جداول البيانات */
    .data-table-wrapper {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 14px 16px;
        text-align: center;
        background: #fafbfc;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 12px 16px;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background: #fafbfc;
    }

    .badge-admin {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-user {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    /* تنسيقات التقييمات في الجدول */
    .review-user {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .review-user-avatar {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 12px;
    }

    .review-user-name {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.8rem;
    }

    .review-government {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.8rem;
    }

    .review-government:hover {
        text-decoration: underline;
    }

    .review-stars {
        color: var(--warning);
        font-size: 11px;
        white-space: nowrap;
    }

    .review-rating-number {
        background: #f0f4ff;
        padding: 2px 6px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        color: var(--primary);
        margin-left: 4px;
    }

    .review-comment-text {
        max-width: 200px;
        font-size: 0.75rem;
        line-height: 1.4;
        color: var(--text-muted);
    }

    .review-date {
        font-size: 0.7rem;
        color: #9ca3af;
        white-space: nowrap;
    }

    .view-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .view-link:hover {
        color: var(--primary-dark);
        gap: 6px;
    }

    .empty-reviews {
        padding: 40px;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-reviews i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 0 15px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-card {
            padding: 18px;
        }

        .stat-number {
            font-size: 1.6rem;
        }

        .links-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .data-table th,
        .data-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }

        .review-user {
            flex-direction: column;
            gap: 4px;
        }

        .review-comment-text {
            max-width: 120px;
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

<div class="admin-container py-5">
    <!-- بطاقات الإحصائيات -->
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

    <!-- روابط الإدارة السريعة (4 أزرار) -->
    <div class="quick-links">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-cog me-2"></i>
                الإدارة السريعة
            </div>
        </div>
        <div class="links-grid">
            <a href="{{ route('admin.governments') }}" class="link-card">
                <div class="link-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="link-title">إدارة الجهات</div>
                <div class="link-desc">إضافة، تعديل، حذف الجهات الحكومية</div>
            </a>
            <a href="{{ route('admin.services') }}" class="link-card">
                <div class="link-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="link-title">إدارة الخدمات</div>
                <div class="link-desc">إضافة، تعديل، حذف الخدمات</div>
            </a>
            <a href="{{ route('admin.users') }}" class="link-card">
                <div class="link-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="link-title">إدارة المستخدمين</div>
                <div class="link-desc">عرض وإدارة المستخدمين والأدوار</div>
            </a>
            <a href="{{ route('admin.reviews') }}" class="link-card">
                <div class="link-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="link-title">إدارة التقييمات</div>
                <div class="link-desc">عرض وإدارة تقييمات المستخدمين</div>
            </a>
        </div>
    </div>

    <!-- جدول آخر المستخدمين -->
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-user-plus me-2"></i>
            آخر المستخدمين
        </div>
        <a href="{{ route('admin.users') }}" class="view-link">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
    <div class="data-table-wrapper mb-4">
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
                        <td colspan="4" class="empty-reviews">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- جدول آخر التقييمات -->
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-star me-2"></i>
            آخر التقييمات
        </div>
        <a href="{{ route('admin.reviews') }}" class="view-link">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
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
                        <td>
                            <div class="review-user">
                                <div class="review-user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="review-user-name">{{ $review->user->user_name }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('governments.show', $review->government->id) }}" class="review-government">
                                {{ $review->government->name }}
                            </a>
                        </td>
                        <td>
                            <div class="review-stars">
                                <span class="review-rating-number">{{ $review->rating }}</span>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td>
                            @if($review->comment)
                                <div class="review-comment-text">
                                    {{ Str::limit($review->comment, 50) }}
                                </div>
                            @else
                                <span class="text-muted">لا يوجد تعليق</span>
                            @endif
                        </td>
                        <td class="review-date">
                            {{ $review->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-reviews">
                            <i class="fas fa-star"></i>
                            <p>لا توجد تقييمات حتى الآن</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
