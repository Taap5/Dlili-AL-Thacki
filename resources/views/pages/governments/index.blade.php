@extends('layouts.app')

@section('title', 'جميع الجهات الحكومية')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
        --bg-light: #fef9f0;
        --border-light: #eef2f6;
    }

    .governments-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 0;
    }

    /* رأس الصفحة */
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
        margin-bottom: 16px;
    }

    .page-title i {
        color: var(--primary);
        margin-left: 12px;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* شريط البحث والفلترة */
    .filters-bar {
        background: white;
        border-radius: 60px;
        padding: 8px;
        margin-bottom: 32px;
        border: 1px solid var(--border-light);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-input i {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }

    .search-input input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: #fafafa;
    }

    .search-input input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .filter-select {
        min-width: 160px;
    }

    .filter-select select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 50px;
        background: white;
        font-size: 0.9rem;
        cursor: pointer;
        background: #fafafa;
    }

    .filter-select select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .reset-btn {
        background: #f0f4ff;
        border: none;
        border-radius: 50px;
        padding: 12px 24px;
        color: var(--primary);
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reset-btn:hover {
        background: var(--primary);
        color: white;
    }

    /* بطاقات الجهات */
    .governments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .government-card {
        background: white;
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: relative;
    }

    .government-card::before {
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

    .government-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.2);
        border-color: rgba(47, 62, 158, 0.15);
    }

    .government-card:hover::before {
        transform: scaleX(1);
    }

    .card-img-top {
        height: 180px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .government-card:hover .card-img-top {
        transform: scale(1.03);
    }

    .card-body {
        padding: 20px;
    }

    .government-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
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
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .government-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .rating-stars {
        color: #ffc107;
        font-size: 12px;
        white-space: nowrap;
    }

    .reviews-count {
        color: var(--text-muted);
        font-size: 0.7rem;
        margin-right: 4px;
    }

    .category-badge {
        background: #f0f4ff;
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .government-contact {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .contact-info i {
        width: 20px;
        color: var(--primary);
    }

    .btn-details {
        background: transparent;
        border: 1px solid var(--primary);
        border-radius: 40px;
        padding: 10px 20px;
        color: var(--primary);
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
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
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 32px;
        border: 1px solid var(--border-light);
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
        color: #9ca3af;
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

    .btn-home {
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

    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
        color: white;
    }

    /* الترقيم */
    .pagination-wrapper {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 8px 14px;
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
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* استجابة للهواتف */
    @media (max-width: 768px) {
        .governments-container {
            padding: 15px;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .filters-bar {
            flex-direction: column;
            border-radius: 24px;
            padding: 16px;
        }

        .search-input,
        .filter-select {
            width: 100%;
        }

        .governments-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card-img-top {
            height: 150px;
        }

        .government-title {
            font-size: 1rem;
        }
    }
</style>

<div class="governments-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-building"></i>
            جميع الجهات الحكومية
        </h1>
        <p class="page-subtitle">استعرض جميع الجهات الحكومية المسجلة في منصتنا</p>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="filters-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن جهة..." value="{{ request('search') }}">
        </div>
        <div class="filter-select">
            <select id="categoryFilter">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="reset-btn" id="resetBtn">
            <i class="fas fa-undo-alt"></i>
            إعادة تعيين
        </button>
    </div>

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
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-building fa-4x text-muted"></i>
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
                            <i class="fas fa-info-circle me-2"></i>تفاصيل الجهة
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
            <h4>لا توجد جهات حكومية</h4>
            <p>سيتم إضافة جهات جديدة قريباً</p>
            <a href="{{ route('home') }}" class="btn-home">
                <i class="fas fa-home me-2"></i>العودة للصفحة الرئيسية
            </a>
        </div>
    @endif
</div>

<script>
    // البحث والفلترة
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('resetBtn');

    function applyFilters() {
        const search = searchInput ? searchInput.value.trim() : '';
        const category = categoryFilter ? categoryFilter.value : '';

        let url = new URL(window.location.href);

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }

        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFilters);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = '';
            applyFilters();
        });
    }
</script>
@endsection
