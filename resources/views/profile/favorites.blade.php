@extends('layouts.app')

@section('title', 'المفضلة')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --bg-light: #fef9f0;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
    }

    .favorites-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .page-header {
        margin-bottom: 32px;
        text-align: center;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        position: relative;
        display: inline-block;
        margin-bottom: 12px;
    }

    .page-title i {
        color: #dc3545;
        margin-left: 12px;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #dc3545, #ff6b6b);
        border-radius: 3px;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    /* تبويبات الأقسام */
    .tabs-container {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 10px 28px;
        border: none;
        background: #f0f2f5;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn i {
        font-size: 14px;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.25);
    }

    .tab-btn:hover:not(.active) {
        background: #e5e8ec;
        transform: translateY(-1px);
    }

    /* أقسام المحتوى */
    .section-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .section-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 20px;
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

    .section-count {
        background: #f0f2f5;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        color: var(--primary);
    }

    /* بطاقات المفضلة */
    .favorite-card {
        background: #ffffff;
        border-radius: 24px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .favorite-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        border-color: rgba(47, 62, 158, 0.2);
    }

    .favorite-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .favorite-card:hover::before {
        transform: scaleX(1);
    }

    .card-header-icon {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .icon-badge {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-badge i {
        font-size: 24px;
    }

    .icon-badge.government i {
        color: var(--primary);
    }

    .icon-badge.service i {
        color: #388e3c;
    }

    .favorite-card h5 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .favorite-card h5 a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .favorite-card h5 a:hover {
        color: var(--primary);
    }

    .card-description {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .card-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .btn-remove {
        background: transparent;
        border: 1px solid #dc3545;
        border-radius: 30px;
        padding: 6px 16px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-remove:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-1px);
    }

    .btn-details {
        background: transparent;
        border: 1px solid var(--primary);
        border-radius: 30px;
        padding: 6px 16px;
        color: var(--primary);
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-details:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    /* حالة عدم وجود بيانات */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 32px;
        border: 1px solid #f0f0f0;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #f0f2f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 40px;
        color: #dc3545;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .btn-explore {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 40px;
        padding: 10px 28px;
        color: white;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-explore:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
        color: white;
    }

    /* شبكة البطاقات */
    .row-custom {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    @media (max-width: 768px) {
        .row-custom {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .tabs-container {
            gap: 8px;
        }

        .tab-btn {
            padding: 8px 20px;
            font-size: 12px;
        }

        .card-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-remove, .btn-details {
            justify-content: center;
        }
    }
</style>

<div class="favorites-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-heart"></i>
            المفضلة
        </h1>
        <p class="page-subtitle">الجهات والخدمات التي قمت بإضافتها إلى قائمة المفضلات</p>
    </div>

    @php
        $hasGovernments = $governments->count() > 0;
        $hasServices = $services->count() > 0;
    @endphp

    @if($hasGovernments || $hasServices)
        <!-- تبويبات -->
        <div class="tabs-container">
            <button class="tab-btn active" data-tab="all">
                <i class="fas fa-globe"></i>
                الكل ({{ $governments->count() + $services->count() }})
            </button>
            <button class="tab-btn" data-tab="governments">
                <i class="fas fa-building"></i>
                جهات ({{ $governments->count() }})
            </button>
            <button class="tab-btn" data-tab="services">
                <i class="fas fa-concierge-bell"></i>
                خدمات ({{ $services->count() }})
            </button>
        </div>

        <!-- قسم الجهات -->
        <div class="section-content" data-section="governments">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-building me-2"></i>
                    الجهات الحكومية
                </h3>
                <span class="section-count">{{ $governments->count() }} جهة</span>
            </div>

            @if($governments->count() > 0)
                <div class="row-custom">
                    @foreach($governments as $government)
                        <div class="favorite-card">
                            <div class="card-body p-4">
                                <div class="card-header-icon">
                                    <div class="icon-badge government">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="rating-mini">
                                        @php
                                            $avgRating = $government->reviews->avg('rating') ?? 0;
                                        @endphp
                                        @if($avgRating > 0)
                                            <div class="text-warning small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($avgRating))
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="text-muted ms-1">{{ number_format($avgRating, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <h5>
                                    <a href="{{ route('governments.show', $government->id) }}">
                                        {{ $government->name }}
                                    </a>
                                </h5>

                                <div class="card-description">
                                    {{ Str::limit($government->description ?? 'لا يوجد وصف', 100) }}
                                </div>

                                <div class="card-meta">
                                    @if($government->category)
                                        <span><i class="fas fa-tag"></i> {{ $government->category->name }}</span>
                                    @endif
                                    @if($government->created_at)
                                        <span><i class="fas fa-calendar-alt"></i> {{ $government->created_at->format('Y/m/d') }}</span>
                                    @endif
                                </div>

                                <div class="card-actions">
                                    <button class="btn-remove favorite-btn"
                                            data-id="{{ $government->id }}"
                                            data-type="government"
                                            data-url="{{ route('favorite.government.toggle') }}">
                                        <i class="fas fa-heart-broken"></i>
                                        إزالة
                                    </button>
                                    <a href="{{ route('governments.show', $government->id) }}" class="btn-details">
                                        <i class="fas fa-arrow-left"></i>
                                        تفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- قسم الخدمات -->
        <div class="section-content" data-section="services">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-concierge-bell me-2"></i>
                    الخدمات
                </h3>
                <span class="section-count">{{ $services->count() }} خدمة</span>
            </div>

            @if($services->count() > 0)
                <div class="row-custom">
                    @foreach($services as $service)
                        <div class="favorite-card">
                            <div class="card-body p-4">
                                <div class="card-header-icon">
                                    <div class="icon-badge service">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div class="service-stats">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-building"></i> {{ $service->governments->count() }} جهة
                                        </span>
                                    </div>
                                </div>

                                <h5>
                                    <a href="{{ route('services.show', $service->id) }}">
                                        {{ $service->name }}
                                    </a>
                                </h5>

                                <div class="card-description">
                                    {{ Str::limit($service->description ?? 'لا يوجد وصف', 100) }}
                                </div>

                                <div class="card-meta">
                                    @if($service->category)
                                        <span><i class="fas fa-tag"></i> {{ $service->category->name }}</span>
                                    @endif
                                    @if($service->created_at)
                                        <span><i class="fas fa-calendar-alt"></i> {{ $service->created_at->format('Y/m/d') }}</span>
                                    @endif
                                </div>

                                <div class="card-actions">
                                    <button class="btn-remove favorite-btn"
                                            data-id="{{ $service->id }}"
                                            data-type="service"
                                            data-url="{{ route('favorite.service.toggle') }}">
                                        <i class="fas fa-heart-broken"></i>
                                        إزالة
                                    </button>
                                    <a href="{{ route('services.show', $service->id) }}" class="btn-details">
                                        <i class="fas fa-arrow-left"></i>
                                        تفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <!-- حالة عدم وجود مفضلات -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-heart-broken"></i>
            </div>
            <h4>لا توجد مفضلات</h4>
            <p>أضف جهات أو خدمات إلى المفضلة لتظهر هنا</p>
            <a href="/" class="btn-explore">
                <i class="fas fa-compass"></i>
                استعرض الجهات والخدمات
            </a>
        </div>
    @endif
</div>

<script>
    // تبويبات الأقسام
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;

            // تحديث حالة الأزرار
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // عرض القسم المناسب
            if (tab === 'all') {
                document.querySelectorAll('.section-content').forEach(section => {
                    section.classList.add('active');
                });
            } else if (tab === 'governments') {
                document.querySelectorAll('.section-content').forEach(section => {
                    section.classList.remove('active');
                });
                document.querySelector('[data-section="governments"]').classList.add('active');
            } else if (tab === 'services') {
                document.querySelectorAll('.section-content').forEach(section => {
                    section.classList.remove('active');
                });
                document.querySelector('[data-section="services"]').classList.add('active');
            }
        });
    });

    // تهيئة عرض المحتوى عند تحميل الصفحة - عرض جميع الأقسام (الكل)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.add('active');
        });
    });
</script>
@endsection

@push('scripts')
<script src="{{ asset('js/favorite.js') }}"></script>
@endpush
