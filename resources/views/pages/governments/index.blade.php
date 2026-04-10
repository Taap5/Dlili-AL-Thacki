@extends('layouts.app')

@section('title', 'جميع الجهات الحكومية - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --border-light: rgba(47, 62, 158, 0.1);
        --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.12);
    }

    /* خلفية الصفحة */
    .governments-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .governments-page::before {
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

    .governments-page::after {
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

    .governments-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
        z-index: 2;
    }

    /* رأس الصفحة */
    .page-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.5rem;
    }

    .page-title i {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-left: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* شريط البحث والفلترة - Form */
    .filters-card {
        background: white;
        border-radius: 60px;
        padding: 0.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

    .search-wrapper {
        flex: 2;
        min-width: 200px;
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-light);
        font-size: 0.9rem;
    }

    .search-wrapper input {
        width: 100%;
        padding: 0.8rem 2.5rem 0.8rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 50px;
        font-size: 0.85rem;
        transition: all 0.2s;
        background: #fafafa;
        box-sizing: border-box;
    }

    .search-wrapper input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .filter-wrapper {
        flex: 1;
        min-width: 150px;
    }

    .filter-wrapper select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 50px;
        background: #fafafa;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .filter-wrapper select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .btn-search {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 50px;
        padding: 0.8rem 1.8rem;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
    }

    .reset-btn {
        background: #f0f4ff;
        border: none;
        border-radius: 50px;
        padding: 0.8rem 1.5rem;
        color: var(--primary);
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .reset-btn:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
    }

    /* رسالة نتائج البحث */
    .search-info {
        background: #f0f4ff;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        margin-bottom: 1.5rem;
        text-align: center;
        direction: rtl;
        font-size: 0.85rem;
        color: var(--primary);
    }

    .search-info i {
        margin-left: 0.3rem;
    }

    /* ===== شبكة البطاقات ===== */
    .governments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
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
        width: 100%;
        box-sizing: border-box;
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

    .government-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .government-title a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .government-title a:hover {
        color: var(--primary);
    }

    .government-description {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .government-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
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

    .category-badge {
        background: #f0f4ff;
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .government-contact {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .contact-info i {
        width: 20px;
        color: var(--primary);
        font-size: 0.75rem;
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
        margin-top: auto;
        box-sizing: border-box;
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
        .governments-page {
            padding: 1rem 0;
        }

        .governments-container {
            padding: 0 1rem;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .filters-card {
            flex-direction: column;
            border-radius: 24px;
            padding: 1rem;
        }

        .search-wrapper,
        .filter-wrapper {
            width: 100%;
        }

        .btn-search,
        .reset-btn {
            width: 100%;
            justify-content: center;
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

        .government-meta {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-info {
            font-size: 0.75rem;
        }
    }
</style>

<div class="governments-page">
    <div class="governments-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-building"></i>
                جميع الجهات الحكومية
            </h1>
            <p class="page-subtitle">استعرض جميع الجهات الحكومية المسجلة في منصتنا</p>
        </div>

        <!-- شريط البحث والفلترة - Form -->
        <form method="GET" action="{{ route('governments.index') }}" class="filters-card">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="ابحث عن جهة..." value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select name="category">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ route('governments.index') }}" class="reset-btn">
                <i class="fas fa-undo-alt"></i> إعادة تعيين
            </a>
        </form>

        <!-- رسالة نتائج البحث -->
        @if(request('search') || request('category'))
            <div class="search-info">
                @if(request('search'))
                    <i class="fas fa-search"></i> نتائج البحث عن: <strong>"{{ request('search') }}"</strong>
                @endif
                @if(request('category') && !request('search'))
                    <i class="fas fa-filter"></i> عرض جهات التصنيف المحدد
                @endif
                @if(request('search') && request('category'))
                    <i class="fas fa-filter"></i> مع الفلترة على التصنيف المحدد
                @endif
                - <strong>{{ $governments->total() }}</strong> جهة
            </div>
        @endif

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
                                 class="card-img-top"
                                 alt="{{ $gov->name }}">
                        @else
                            <div class="card-img-placeholder">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h3 class="government-title">
                                <a href="{{ route('governments.show', $gov->id) }}">
                                    {{ $gov->name }}
                                </a>
                            </h3>

                            <p class="government-description">
                                {{ Str::limit($gov->description ?? 'لا يوجد وصف للجهة', 100) }}
                            </p>

                            <div class="government-meta">
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
                                <span class="category-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $gov->category->name ?? 'غير مصنف' }}
                                </span>
                            </div>

                            @if($gov->contact_number || $gov->work_hours)
                                <div class="government-contact">
                                    @if($gov->contact_number)
                                        <div class="contact-info">
                                            <i class="fas fa-phone-alt"></i>
                                            <span>{{ $gov->contact_number }}</span>
                                        </div>
                                    @endif
                                    @if($gov->work_hours)
                                        <div class="contact-info">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $gov->work_hours }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('governments.show', $gov->id) }}" class="btn-details">
                                <i class="fas fa-info-circle me-1"></i> تفاصيل الجهة
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $governments->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h4>لا توجد جهات حكومية</h4>
                <p>
                    @if(request('search'))
                        لا توجد نتائج مطابقة لـ "{{ request('search') }}"
                    @else
                        لم يتم إضافة أي جهات بعد
                    @endif
                </p>
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home me-1"></i> العودة للصفحة الرئيسية
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
