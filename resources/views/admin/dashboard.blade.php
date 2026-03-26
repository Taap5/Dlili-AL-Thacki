@extends('layouts.app')

@section('title', 'لوحة تحكم المسؤول')

@section('content')
<style>
    main {
        padding-top: 100px !important;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .stat-icon {
        font-size: 40px;
        color: #2f3e9e;
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        margin: 5px 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 14px;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .link-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        text-align: center;
        text-decoration: none;
        color: #333;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: block;
    }

    .link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .link-icon {
        font-size: 48px;
        color: #2f3e9e;
        margin-bottom: 15px;
    }

    .link-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .link-desc {
        font-size: 13px;
        color: #6c757d;
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .data-table th,
    .data-table td {
        padding: 12px 16px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .data-table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .badge-admin {
        background: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-user {
        background: #2f3e9e;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .data-table th, .data-table td {
            padding: 8px 10px;
            font-size: 12px;
        }
    }
</style>

<div class="container py-5">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-number">{{ $stats['governments_count'] }}</div>
            <div class="stat-label">الجهات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
            <div class="stat-number">{{ $stats['services_count'] }}</div>
            <div class="stat-label">الخدمات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $stats['users_count'] }}</div>
            <div class="stat-label">المستخدمين</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-number">{{ $stats['reviews_count'] }}</div>
            <div class="stat-label">التقييمات</div>
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
            <div class="link-desc">عرض وإدارة المستخدمين</div>
        </a>
    </div>

    <div class="section-title">
        <i class="fas fa-user-plus"></i> آخر المستخدمين
    </div>
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
            @foreach($stats['latest_users'] as $user)
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
            @endforeach
        </tbody>
    </table>

    <div class="section-title mt-4">
        <i class="fas fa-star"></i> آخر التقييمات
    </div>
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
            @foreach($stats['latest_reviews'] as $review)
                <tr>
                    <td>{{ $review->user->user_name }}</td>
                    <td>{{ $review->government->name }}</td>
                    <td>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </td>
                    <td>{{ Str::limit($review->comment, 30) ?: 'لا يوجد' }}</td>
                    <td>{{ $review->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
