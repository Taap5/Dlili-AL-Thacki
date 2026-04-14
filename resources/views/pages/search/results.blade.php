@extends('layouts.app')

@section('title', 'نتائج البحث - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-light: rgba(47, 62, 158, 0.1);
        --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
    }

    .search-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .search-page::before {
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

    .search-page::after {
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

    .container-custom {
        max-width: 1200px;
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

    /* Search Summary */
    .search-summary {
        background: white;
        border-radius: 28px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
    }

    .search-query {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.5rem;
    }

    .search-query span {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .search-stats {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* Filter Sidebar */
    .filter-sidebar {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        position: sticky;
        top: 100px;
        border: 1px solid var(--border-light);
    }

    .filter-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--primary);
    }

    .filter-select {
        width: 100%;
        padding: 0.7rem;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .rating-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 12px;
    }

    .rating-option:hover {
        background: #f8fafc;
    }

    .rating-option.active {
        background: #f0f4ff;
    }

    .rating-option input {
        margin: 0;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 0.8rem;
    }

    .type-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .type-btn {
        flex: 1;
        padding: 0.6rem;
        border: 1px solid var(--border-light);
        background: white;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .type-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-color: transparent;
    }

    /* Sort Bar */
    .sort-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 0.5rem 0;
    }

    .result-count {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .sort-select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 40px;
        font-size: 0.8rem;
        background: white;
    }

    /* Result Cards */
    .results-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .result-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border-light);
        transition: all 0.3s;
        overflow: hidden;
    }

    .result-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow);
        border-color: var(--primary);
    }

    .result-content {
        display: flex;
        gap: 1rem;
        padding: 1.2rem;
        flex-wrap: wrap;
    }

    .result-image {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .result-image-placeholder {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .result-image-placeholder i {
        font-size: 2rem;
        color: var(--primary-light);
    }

    .result-info {
        flex: 1;
        min-width: 200px;
    }

    .result-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .result-title a {
        color: var(--text-dark);
        text-decoration: none;
    }

    .result-title a:hover {
        color: var(--primary);
    }

    .result-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .badge-government {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-service {
        background: #d1fae5;
        color: #065f46;
    }

    .result-description {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .result-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
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

    .result-rating {
        text-align: center;
        min-width: 80px;
    }

    .rating-stars-large {
        color: #fbbf24;
        font-size: 0.7rem;
        margin-bottom: 0.2rem;
    }

    .rating-value {
        font-weight: 700;
        font-size: 1rem;
    }

    .result-action {
        display: flex;
        align-items: center;
    }

    .btn-view {
        padding: 0.5rem 1.2rem;
        background: transparent;
        border: 2px solid var(--primary);
        border-radius: 40px;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-view:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 28px;
        padding: 4rem 2rem;
        text-align: center;
        border: 1px solid var(--border-light);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
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
        margin-bottom: 0.5rem;
    }

    .btn-home {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 40px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-custom {
            padding: 0 1rem;
        }

        .search-query {
            font-size: 1.3rem;
        }

        .result-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .result-info {
            text-align: center;
        }

        .result-meta {
            justify-content: center;
        }

        .result-rating {
            margin-top: 0.5rem;
        }

        .filter-sidebar {
            position: relative;
            top: 0;
            margin-bottom: 1rem;
        }
    }
</style>

<div class="search-page">
    <div class="container-custom">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span class="separator">/</span>
            <span class="current">نتائج البحث</span>
        </div>

        <!-- Search Summary -->
        <div class="search-summary">
            <h1 class="search-query">
                نتائج البحث عن: <span>"{{ $query }}"</span>
            </h1>
            @if(isset($results) && $results->count())
                <p class="search-stats">
                    تم العثور على <strong>{{ $results->count() }}</strong> نتيجة مطابقة
                </p>
            @endif
        </div>

        @if(!isset($results) || $results->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4>لم يتم العثور على نتائج مطابقة</h4>
                <p class="text-muted">جرّب البحث بكلمات أخرى أو استخدم التصنيفات لتضييق نطاق البحث</p>
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home"></i> العودة للرئيسية
                </a>
            </div>
        @else
            <div class="row">
                <!-- Filter Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="filter-sidebar">
                        <!-- Category Filter -->
                        <div class="filter-title">
                            <i class="fas fa-tag"></i> التصنيف
                        </div>
                        <select id="categoryFilter" class="filter-select" onchange="updateFilters()">
                            <option value="">جميع التصنيفات</option>
                            @foreach(\App\Models\GovernmentCategory::all() as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Rating Filter -->
                        <div class="filter-title mt-3">
                            <i class="fas fa-star"></i> التقييم
                        </div>
                        @php $ratingFilter = request('rating', ''); @endphp
                        <div class="rating-option {{ $ratingFilter == '' ? 'active' : '' }}" onclick="setRatingFilter('')">
                            <input type="radio" name="rating" value="" {{ $ratingFilter == '' ? 'checked' : '' }}>
                            <span>جميع التقييمات</span>
                        </div>
                        @for($i = 5; $i >= 1; $i--)
                            <div class="rating-option {{ $ratingFilter == $i ? 'active' : '' }}" onclick="setRatingFilter({{ $i }})">
                                <input type="radio" name="rating" value="{{ $i }}" {{ $ratingFilter == $i ? 'checked' : '' }}>
                                <span class="rating-stars">
                                    @for($j = 1; $j <= 5; $j++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </span>
                                <span>({{ $i }}+ نجوم)</span>
                            </div>
                        @endfor

                        <!-- Type Filter -->
                        <div class="filter-title mt-3">
                            <i class="fas fa-filter"></i> نوع النتائج
                        </div>
                        <div class="type-buttons">
                            <button class="type-btn {{ request('type', 'all') == 'all' ? 'active' : '' }}" onclick="setType('all')">الكل</button>
                            <button class="type-btn {{ request('type') == 'governments' ? 'active' : '' }}" onclick="setType('governments')">جهات</button>
                            <button class="type-btn {{ request('type') == 'services' ? 'active' : '' }}" onclick="setType('services')">خدمات</button>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    <!-- Sort Bar -->
                    <div class="sort-bar">
                        <div class="result-count">
                            <i class="fas fa-chart-line"></i> {{ $results->count() }} نتيجة
                        </div>
                        <select id="sortBy" class="sort-select" onchange="updateFilters()">
                            <option value="relevance" {{ request('sort', 'relevance') == 'relevance' ? 'selected' : '' }}>الأكثر صلة</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                            <option value="most_governments" {{ request('sort') == 'most_governments' ? 'selected' : '' }}>الأكثر جهات</option>
                        </select>
                    </div>

                    <!-- Results Grid -->
                    <div class="results-grid">
                        @foreach($results as $result)
                            @php
                                $isGovernment = $result['type'] == 'government';
                                $url = $isGovernment ? route('governments.show', $result['id']) : route('services.show', $result['id']);
                                $firstImage = null;
                                if ($isGovernment && isset($result['images']) && $result['images']) {
                                    $images = is_array($result['images']) ? $result['images'] : json_decode($result['images'], true);
                                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                }
                            @endphp
                            <div class="result-card">
                                <div class="result-content">
                                    <!-- Image -->
                                    @if($isGovernment && $firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}" loading="lazy" class="result-image" alt="{{ $result['name'] }}">
                                    @else
                                        <div class="result-image-placeholder">
                                            <i class="fas {{ $isGovernment ? 'fa-building' : 'fa-concierge-bell' }}"></i>
                                        </div>
                                    @endif

                                    <!-- Info -->
                                    <div class="result-info">
                                        <div class="result-title">
                                            <a href="{{ $url }}">{{ $result['name'] }}</a>
                                        </div>
                                        <div class="result-badge {{ $isGovernment ? 'badge-government' : 'badge-service' }}">
                                            <i class="fas {{ $isGovernment ? 'fa-building' : 'fa-concierge-bell' }}"></i>
                                            {{ $isGovernment ? 'جهة حكومية' : 'خدمة' }}
                                        </div>
                                        @if(isset($result['description']) && $result['description'])
                                            <p class="result-description">{{ Str::limit($result['description'], 120) }}</p>
                                        @endif
                                        <div class="result-meta">
                                            @if($isGovernment && isset($result['address']) && $result['address'])
                                                <div class="meta-item">
                                                    <i class="fas fa-location-dot"></i>
                                                    <span>{{ Str::limit($result['address'], 40) }}</span>
                                                </div>
                                            @endif
                                            @if(!$isGovernment && isset($result['governments_count']) && $result['governments_count'] > 0)
                                                <div class="meta-item">
                                                    <i class="fas fa-building"></i>
                                                    <span>{{ $result['governments_count'] }} جهة تقدم الخدمة</span>
                                                </div>
                                            @endif
                                            @if(isset($result['created_at']))
                                                <div class="meta-item">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span>{{ \Carbon\Carbon::parse($result['created_at'])->diffForHumans() }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Rating (for governments) -->
                                    @if($isGovernment && isset($result['rating']) && $result['rating'] > 0)
                                        <div class="result-rating">
                                            <div class="rating-stars-large">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            </div>
                                            <div class="rating-value">{{ number_format($result['rating'], 1) }}</div>
                                            <div class="rating-count">({{ $result['reviews_count'] }} تقييم)</div>
                                        </div>
                                    @endif

                                    <!-- Action -->
                                    <div class="result-action">
                                        <a href="{{ $url }}" class="btn-view">
                                            <i class="fas fa-arrow-left"></i> عرض
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function updateFilters() {
        const url = new URL(window.location.href);
        const query = "{{ $query }}";
        const categoryId = document.getElementById('categoryFilter').value;
        const rating = document.querySelector('input[name="rating"]:checked')?.value || '';
        const sort = document.getElementById('sortBy').value;
        const type = "{{ request('type', 'all') }}";

        let newUrl = `{{ route('search') }}?query=${encodeURIComponent(query)}`;
        if (categoryId) newUrl += `&category_id=${categoryId}`;
        if (rating) newUrl += `&rating=${rating}`;
        if (sort && sort !== 'relevance') newUrl += `&sort=${sort}`;
        if (type && type !== 'all') newUrl += `&type=${type}`;

        window.location.href = newUrl;
    }

    function setRatingFilter(value) {
        document.querySelectorAll('input[name="rating"]').forEach(radio => {
            radio.checked = radio.value == value;
        });
        updateFilters();
    }

    function setType(type) {
        const url = new URL(window.location.href);
        if (type === 'all') {
            url.searchParams.delete('type');
        } else {
            url.searchParams.set('type', type);
        }
        window.location.href = url.toString();
    }
</script>
@endsection
