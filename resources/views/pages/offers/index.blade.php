@extends('layouts.app')

@section('title', 'العروض الخاصة - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-light: rgba(47, 62, 158, 0.1);
        --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.12);
    }

    .offers-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .offers-page::before {
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

    .offers-page::after {
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

    /* رأس الصفحة */
    .offers-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 2;
    }

    .offers-title {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.5rem;
    }

    .offers-title i {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .offers-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* شريط الفلترة */
    .filters-card {
        background: white;
        border-radius: 60px;
        padding: 0.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        position: relative;
        z-index: 2;
    }

    .filters-card .form-select,
    .filters-card .form-control {
        border: 1px solid var(--border-light);
        border-radius: 50px;
        padding: 0.7rem 1rem;
        font-size: 0.85rem;
        background: #fafafa;
    }

    .filters-card .form-select:focus,
    .filters-card .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
        background: white;
    }

    .filters-card label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        margin-right: 0.5rem;
    }

    .btn-search {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 50px;
        padding: 0.7rem 1.5rem;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
    }

    /* ===== بطاقات العروض ===== */
    .offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .offer-card {
        background: white;
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .offer-card::before {
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

    .offer-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: var(--card-shadow);
    }

    .offer-card:hover::before {
        transform: scaleX(1);
    }

    .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .offer-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .offer-card:hover .offer-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .offer-card:hover .offer-icon i {
        color: white;
    }

    .offer-icon i {
        font-size: 1.8rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .offer-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-dark);
    }

    .offer-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .offer-description {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .offer-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .meta-item i {
        color: var(--primary);
        font-size: 0.7rem;
    }

    .btn-offer-details {
        background: transparent;
        border: 2px solid var(--primary);
        border-radius: 40px;
        padding: 0.6rem 1.2rem;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s;
        width: 100%;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: auto;
    }

    .btn-offer-details:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.2);
    }

    .permanent-badge {
        background: #10b981;
        color: white;
    }

    .limited-badge {
        background: #f59e0b;
        color: white;
    }

    /* حالة عدم وجود بيانات */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 32px;
        border: 1px solid var(--border-light);
        position: relative;
        z-index: 2;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: #f0f4ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 2.5rem;
        color: var(--primary-light);
    }

    .empty-state h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .btn-home {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 40px;
        padding: 0.7rem 1.8rem;
        color: white;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
        color: white;
    }

    /* الترقيم */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: #f0f4ff;
        border-color: var(--primary);
        color: var(--primary);
    }

    .page-link.active {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-color: transparent;
        color: white;
    }

    /* استجابة */
    @media (max-width: 768px) {
        .offers-page {
            padding: 1rem 0;
        }

        .offers-title {
            font-size: 1.6rem;
        }

        .filters-card {
            border-radius: 24px;
            padding: 1rem;
        }

        .filters-card .row {
            gap: 0.5rem;
        }

        .offers-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<div class="offers-page">
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="offers-header">
            <h1 class="offers-title">
                <i class="fas fa-gift me-2"></i>
                العروض والمميزات الخاصة
            </h1>
            <p class="offers-subtitle">اكتشف العروض والتخفيضات والخدمات المجانية المقدمة من الجهات الحكومية</p>
        </div>

        <!-- شريط الفلترة -->
        <div class="filters-card">
            <form method="GET" action="{{ route('offers.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">نوع العرض</label>
                    <select name="type" class="form-select">
                        <option value="">جميع الأنواع</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الفئة المستهدفة</label>
                    <input type="text" name="audience" class="form-control" placeholder="مثال: كبار السن, مرضى السكر" value="{{ request('audience') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn-search w-100">
                        <i class="fas fa-search me-1"></i> بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- قائمة العروض -->
        @if($offers->count() > 0)
            <div class="offers-grid">
                @foreach($offers as $offer)
                    <div class="offer-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="offer-icon">
                                    <i class="{{ $offer->icon ?? 'fas fa-tag' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="offer-title">{{ $offer->title }}</h3>
                                    <span class="offer-badge">{{ $types[$offer->offer_type] ?? $offer->offer_type }}</span>
                                </div>
                            </div>

                            <p class="offer-description">
                                {{ Str::limit($offer->description, 100) }}
                            </p>

                            <div class="offer-meta">
                                @if($offer->target_audience)
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ Str::limit($offer->target_audience, 30) }}</span>
                                    </div>
                                @endif
                                <div class="meta-item">
                                    <i class="fas fa-building"></i>
                                    <span>{{ $offer->government->name }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                @if($offer->is_permanent)
                                    <span class="badge permanent-badge">عرض مستمر</span>
                                @else
                                    <span class="badge limited-badge">عرض لفترة محدودة</span>
                                @endif
                            </div>

                            <a href="{{ route('offers.show', $offer->id) }}" class="btn-offer-details">
                                <i class="fas fa-info-circle me-1"></i> تفاصيل العرض
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $offers->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <h4>لا توجد عروض حالياً</h4>
                <p>سيتم إضافة العروض الجديدة قريباً</p>
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home me-1"></i> العودة للصفحة الرئيسية
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
