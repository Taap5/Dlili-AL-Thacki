@extends('layouts.app')

@section('title', $category->name . ' - دليلي الذكي')

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

    .category-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .category-page::before {
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

    .category-page::after {
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
    .category-header {
        background: white;
        border-radius: 28px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
        position: relative;
        z-index: 2;
    }

    .category-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .category-icon i {
        font-size: 2rem;
        color: var(--primary);
    }

    .category-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.5rem;
    }

    .category-description {
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.6;
        max-width: 80%;
    }

    .category-stats {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-radius: 40px;
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ===== بطاقات الجهات ===== */
    .governments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .government-card {
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

    .government-card::before {
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

    .government-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: var(--card-shadow);
    }

    .government-card:hover::before {
        transform: scaleX(1);
    }

    .card-img-top {
        height: 180px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .government-card:hover .card-img-top {
        transform: scale(1.03);
    }

    .card-img-placeholder {
        height: 180px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-img-placeholder i {
        font-size: 4rem;
        color: var(--primary-light);
    }

    .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .card-title a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .card-title a:hover {
        color: var(--primary);
    }

    .card-text {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rating-stars {
        color: #ffc107;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .reviews-count {
        color: var(--text-muted);
        font-size: 0.7rem;
        margin-right: 0.25rem;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .contact-info i {
        color: var(--primary);
        width: 20px;
    }

    .work-hours {
        font-size: 0.7rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .work-hours i {
        color: var(--primary);
    }

    .btn-details {
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
        margin-top: 1rem;
    }

    .btn-details:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.2);
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

    /* Breadcrumb */
    .breadcrumb-custom {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
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

    /* استجابة */
    @media (max-width: 768px) {
        .category-page {
            padding: 1rem 0;
        }

        .category-header {
            padding: 1.5rem;
            text-align: center;
        }

        .category-description {
            max-width: 100%;
        }

        .category-title {
            font-size: 1.5rem;
        }

        .governments-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .card-img-top,
        .card-img-placeholder {
            height: 150px;
        }
    }

    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }

        .category-header {
            padding: 1rem;
        }
    }
</style>

<div class="category-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span class="separator">/</span>
            <a href="{{ route('services.index') }}">الخدمات</a>
            <span class="separator">/</span>
            <span class="current">{{ $category->name }}</span>
        </div>

        <!-- رأس الصفحة -->
        <div class="category-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="category-icon">
                        <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                    </div>
                    <h1 class="category-title">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @endif
                </div>
                <div class="category-stats">
                    <i class="fas fa-building"></i>
                    {{ $governments->total() }} جهة
                </div>
            </div>
        </div>

        <!-- عرض الجهات -->
        @if($governments->count() > 0)
            <div class="governments-grid">
                @foreach($governments as $gov)
                    @php
                        $avgRating = $gov->reviews->avg('rating') ?? 0;
                        $reviewsCount = $gov->reviews->count();
                        $images = $gov->images ?? [];
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                    @endphp
                    <div class="government-card">
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage) }}" 
                                 class="card-img-top" loading="lazy"
                                 alt="{{ $gov->name }}">
                        @else
                            <div class="card-img-placeholder" loading="lazy">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="{{ route('governments.show', $gov->id) }}">
                                    {{ $gov->name }}
                                </a>
                            </h3>

                            <p class="card-text">
                                {{ Str::limit($gov->description ?? 'لا يوجد وصف للجهة', 100) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($avgRating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="reviews-count">({{ $reviewsCount }})</span>
                                </div>

                                @if($gov->contact_number)
                                    <div class="contact-info">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ Str::limit($gov->contact_number, 12) }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($gov->work_hours)
                                <div class="work-hours">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ Str::limit($gov->work_hours, 30) }}</span>
                                </div>
                            @endif

                            <a href="{{ route('governments.show', $gov->id) }}" class="btn-details">
                                <i class="fas fa-info-circle me-1"></i> تفاصيل الجهة
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- روابط التصفح -->
            <div class="pagination-wrapper">
                {{ $governments->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h4>لا توجد جهات في هذا التصنيف</h4>
                <p>سيتم إضافة جهات جديدة قريباً</p>
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home me-1"></i> العودة للصفحة الرئيسية
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
