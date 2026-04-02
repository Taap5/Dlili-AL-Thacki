@extends('layouts.app')

@section('title', 'نتائج البحث')

@section('content')
<style>
    .filter-sidebar {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 20px;
        position: sticky;
        top: 100px;
    }
    .filter-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #2f3e9e;
    }
    .rating-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
        cursor: pointer;
        transition: all 0.2s;
    }
    .rating-filter:hover {
        background: #e9ecef;
        padding-right: 8px;
        border-radius: 8px;
    }
    .rating-filter.active {
        background: #e9ecef;
        padding-right: 8px;
        border-radius: 8px;
    }
    .rating-stars {
        color: #ffc107;
        font-size: 14px;
    }
    .sort-btn {
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .sort-btn.active {
        background: #2f3e9e;
        color: white;
    }
    .result-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 16px;
    }
    .result-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .result-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
    }
    .type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
    }
    .type-government {
        background: #e3f2fd;
        color: #1976d2;
    }
    .type-service {
        background: #e8f5e9;
        color: #388e3c;
    }
    .tab-btn {
        padding: 8px 20px;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f1f3f5;
        color: #6c757d;
    }
    .tab-btn.active {
        background: #2f3e9e;
        color: white;
    }
</style>

<div class="container py-5">
    {{-- شريط ملخص البحث --}}
    <div class="search-summary mb-4 text-center">
        <h3 class="fw-bold text-primary">
            نتائج البحث عن: <span class="text-dark">"{{ $query }}"</span>
        </h3>

        @if(isset($results) && $results->count())
            <p class="text-muted mt-2">
                تم العثور على {{ $results->count() }} نتيجة مطابقة
            </p>
        @endif
    </div>

    @if(!isset($results) || $results->isEmpty())
        {{-- حالة عدم وجود نتائج --}}
        <div class="alert alert-light text-center p-5 shadow-sm">
            <h5 class="fw-bold mb-3">لم يتم العثور على نتائج مطابقة</h5>
            <p class="text-muted mb-4">
                جرّب البحث بكلمات أخرى أو استخدم التصنيفات لتضييق نطاق البحث
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary">العودة للبحث</a>
        </div>
    @else
        <div class="row">
            {{-- الفلاتر الجانبية --}}
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    {{-- فلتر التصنيف --}}
                    <div class="mb-4">
                        <div class="filter-title">
                            <i class="fas fa-tag me-1"></i> التصنيف
                        </div>
                        <select id="categoryFilter" class="form-select form-select-sm" onchange="updateFilters()">
                            <option value="">جميع التصنيفات</option>
                            @foreach(\App\Models\GovernmentCategory::all() as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- فلتر التقييم --}}
                    <div class="mb-4">
                        <div class="filter-title">
                            <i class="fas fa-star me-1"></i> التقييم
                        </div>
                        @php $ratingFilter = request('rating', ''); @endphp
                        <div class="rating-filter {{ $ratingFilter == '' ? 'active' : '' }}" onclick="setRatingFilter('')">
                            <input type="radio" name="rating" value="" {{ $ratingFilter == '' ? 'checked' : '' }}> جميع التقييمات
                        </div>
                        @for($i = 5; $i >= 1; $i--)
                            <div class="rating-filter {{ $ratingFilter == $i ? 'active' : '' }}" onclick="setRatingFilter({{ $i }})">
                                <input type="radio" name="rating" value="{{ $i }}" {{ $ratingFilter == $i ? 'checked' : '' }}>
                                <span class="rating-stars">
                                    @for($j = 1; $j <= 5; $j++)
                                        @if($j <= $i)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="small">({{ $i }}+ نجوم)</span>
                            </div>
                        @endfor
                    </div>

                    {{-- نوع النتائج --}}
                    <div class="mb-4">
                        <div class="filter-title">
                            <i class="fas fa-filter me-1"></i> نوع النتائج
                        </div>
                        <div class="d-flex gap-2">
                            <button class="tab-btn {{ request('type', 'all') == 'all' ? 'active' : '' }}" onclick="setType('all')">الكل</button>
                            <button class="tab-btn {{ request('type') == 'governments' ? 'active' : '' }}" onclick="setType('governments')">جهات</button>
                            <button class="tab-btn {{ request('type') == 'services' ? 'active' : '' }}" onclick="setType('services')">خدمات</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- نتائج البحث --}}
            <div class="col-lg-9">
                {{-- ترتيب النتائج --}}
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i> {{ $results->count() }} نتيجة
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">ترتيب حسب:</span>
                        <select id="sortBy" class="form-select form-select-sm" style="width: auto;" onchange="updateFilters()">
                            <option value="relevance" {{ request('sort', 'relevance') == 'relevance' ? 'selected' : '' }}>الأكثر صلة</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                            <option value="most_governments" {{ request('sort') == 'most_governments' ? 'selected' : '' }}>الأكثر جهات</option>
                        </select>
                    </div>
                </div>

                {{-- عرض النتائج --}}
                <div class="row g-3">
                    @foreach($results as $result)
                        <div class="col-12">
                            <div class="card result-card shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex gap-3">
                                        {{-- صورة الجهة (إذا كانت جهة) --}}
                                        @if($result['type'] == 'government')
                                            @php
                                                $gov = \App\Models\Government::find($result['id']);
                                                $firstImage = $gov && $gov->images && count($gov->images) > 0 ? $gov->images[0] : null;
                                            @endphp
                                            @if($firstImage)
                                                <img src="{{ asset('storage/' . $firstImage) }}" class="result-image" alt="{{ $result['name'] }}">
                                            @else
                                                <div class="result-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-building fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="result-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-concierge-bell fa-2x text-primary"></i>
                                            </div>
                                        @endif

                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                                <div>
                                                    <h5 class="fw-bold mb-1">
                                                        <a href="{{ $result['type'] == 'government' ? route('governments.show', $result['id']) : route('services.show', $result['id']) }}"
                                                           class="text-decoration-none text-dark">
                                                            {{ $result['name'] }}
                                                        </a>
                                                    </h5>
                                                    <span class="badge {{ $result['type'] == 'government' ? 'type-government' : 'type-service' }}">
                                                        <i class="fas {{ $result['type'] == 'government' ? 'fa-building' : 'fa-concierge-bell' }} me-1"></i>
                                                        {{ $result['type'] == 'government' ? 'جهة حكومية' : 'خدمة' }}
                                                    </span>
                                                </div>

                                                {{-- عرض التقييم للجهات --}}
                                                @if($result['type'] == 'government' && isset($result['rating']) && $result['rating'] > 0)
                                                    <div class="text-end">
                                                        <div class="text-warning small">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= round($result['rating']))
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ number_format($result['rating'], 1) }} ({{ $result['reviews_count'] }} تقييم)
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- معلومات إضافية --}}
                                            @if($result['type'] == 'service' && isset($result['governments_count']) && $result['governments_count'] > 0)
                                                <div class="mt-2">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $result['governments_count'] }} جهة تقدم هذه الخدمة
                                                    </span>
                                                </div>
                                            @endif

                                            @if($result['type'] == 'government')
                                                <div class="mt-2 small text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    تم الإضافة: {{ \Carbon\Carbon::parse($result['created_at'])->diffForHumans() }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="align-self-center">
                                            <a href="{{ $result['type'] == 'government' ? route('governments.show', $result['id']) : route('services.show', $result['id']) }}"
                                               class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="fas fa-arrow-left me-1"></i> عرض
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // تحديث الفلاتر وإعادة التوجيه
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
        // تحديث الراديو
        document.querySelectorAll('input[name="rating"]').forEach(radio => {
            if (radio.value == value) {
                radio.checked = true;
            } else {
                radio.checked = false;
            }
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
