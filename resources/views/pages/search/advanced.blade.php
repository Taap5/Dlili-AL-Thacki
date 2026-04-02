@extends('layouts.app')

@section('title', 'بحث متقدم')

@section('content')
<style>
    .advanced-search-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .advanced-search-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .search-title {
        font-size: 28px;
        font-weight: bold;
        color: #2f3e9e;
        margin-bottom: 30px;
        text-align: center;
    }

    .search-title i {
        margin-left: 10px;
    }

    .form-section {
        margin-bottom: 25px;
    }

    .form-section-title {
        font-weight: 600;
        color: #1a2c3e;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title i {
        color: #2f3e9e;
        font-size: 18px;
    }

    .search-input-large {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid #ddd;
        border-radius: 14px;
        font-size: 16px;
        font-family: "Cairo", sans-serif;
        transition: all 0.2s;
    }

    .search-input-large:focus {
        outline: none;
        border-color: #2f3e9e;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    /* قسم الفلاتر القابلة للطي */
    .filters-wrapper {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .filters-wrapper.collapsed {
        max-height: 0 !important;
        margin: 0;
        padding: 0;
        opacity: 0;
        visibility: hidden;
    }

    .filters-wrapper:not(.collapsed) {
        max-height: 800px;
        opacity: 1;
        visibility: visible;
    }

    .toggle-filters-btn {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 40px;
        padding: 10px 20px;
        color: #2f3e9e;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .toggle-filters-btn:hover {
        background: #e8eaf6;
        border-color: #2f3e9e;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .filter-column {
        flex: 1;
        min-width: 200px;
    }

    .filter-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        cursor: pointer;
    }

    .filter-option input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .filter-option label {
        cursor: pointer;
        margin: 0;
        color: #4a5568;
    }

    .rating-stars {
        color: #ffc107;
        font-size: 14px;
    }

    .search-btn-advanced {
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border: none;
        border-radius: 14px;
        padding: 14px 32px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        justify-content: center;
    }

    .search-btn-advanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(47, 62, 158, 0.3);
    }

    .reset-link {
        text-align: center;
        margin-top: 15px;
    }

    .reset-link a {
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }

    .reset-link a:hover {
        color: #2f3e9e;
    }

    /* تنسيقات النتائج */
    .results-section {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 30px;
        transition: all 0.3s ease;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .results-count {
        font-size: 16px;
        color: #6c757d;
    }

    .results-count strong {
        color: #2f3e9e;
        font-size: 20px;
    }

    .loading-spinner {
        text-align: center;
        padding: 50px;
        display: none;
    }

    .loading-spinner i {
        font-size: 40px;
        color: #2f3e9e;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .result-card {
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .result-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #2f3e9e;
    }

    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .result-name {
        font-size: 18px;
        font-weight: bold;
        color: #1a2c3e;
    }

    .result-name i {
        margin-left: 8px;
        font-size: 18px;
    }

    .result-rating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stars {
        color: #ffc107;
        font-size: 14px;
    }

    .rating-value {
        font-weight: 600;
        color: #1a2c3e;
    }

    .reviews-count {
        color: #6c757d;
        font-size: 12px;
    }

    .result-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 14px;
        color: #6c757d;
    }

    .result-details span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .result-details i {
        width: 16px;
        color: #2f3e9e;
    }

    .result-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .badge-category {
        background: #e8eaf6;
        color: #2f3e9e;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-type {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .result-footer {
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
    }

    .view-btn {
        background: none;
        border: 1px solid #2f3e9e;
        border-radius: 30px;
        padding: 6px 16px;
        color: #2f3e9e;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .view-btn:hover {
        background: #2f3e9e;
        color: white;
    }

    .no-results {
        text-align: center;
        padding: 50px;
        color: #6c757d;
    }

    .no-results i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .page-btn {
        padding: 8px 14px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background: #f0f2ff;
        border-color: #2f3e9e;
    }

    .page-btn.active {
        background: #2f3e9e;
        color: white;
        border-color: #2f3e9e;
    }

    @media (max-width: 768px) {
        .advanced-search-container {
            padding: 15px;
        }

        .advanced-search-card, .results-section {
            padding: 20px;
        }

        .search-title {
            font-size: 24px;
        }

        .filter-group {
            flex-direction: column;
            gap: 15px;
        }

        .result-header {
            flex-direction: column;
        }

        .result-details {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="container py-5">
    <div class="advanced-search-container">
        {{-- نموذج البحث --}}
        <div class="advanced-search-card">
            <div class="search-title">
                <i class="fas fa-sliders-h"></i>
                بحث متقدم
            </div>

            {{-- حقل البحث --}}
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-search"></i>
                    <span>ابحث عن</span>
                </div>
                <input type="text"
                       id="searchQuery"
                       class="search-input-large"
                       placeholder="اكتب كلمة البحث..."
                       autocomplete="off">
            </div>

            {{-- زر طي/إظهار الفلاتر --}}
            <div style="text-align: center;">
                <button type="button" class="toggle-filters-btn" id="toggleFiltersBtn">
                    <i class="fas fa-sliders-h"></i>
                    <span id="toggleFiltersText">إخفاء الفلاتر المتقدمة</span>
                    <i class="fas fa-chevron-up" id="toggleFiltersIcon"></i>
                </button>
            </div>

            {{-- الفلاتر (قابلة للطي) --}}
            <div id="filtersWrapper" class="filters-wrapper">
                <div class="filter-group">
                    {{-- فلتر التصنيف --}}
                    <div class="filter-column">
                        <div class="form-section-title">
                            <i class="fas fa-tag"></i>
                            <span>التصنيف</span>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="category_id" value="" id="cat_all" checked>
                            <label for="cat_all">جميع التصنيفات</label>
                        </div>
                        @foreach(\App\Models\GovernmentCategory::all() as $cat)
                            <div class="filter-option">
                                <input type="radio" name="category_id" value="{{ $cat->id }}" id="cat_{{ $cat->id }}">
                                <label for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    {{-- فلتر التقييم --}}
                    <div class="filter-column">
                        <div class="form-section-title">
                            <i class="fas fa-star"></i>
                            <span>التقييم</span>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="rating" value="" id="rating_all" checked>
                            <label for="rating_all">جميع التقييمات</label>
                        </div>
                        @for($i = 5; $i >= 1; $i--)
                            <div class="filter-option">
                                <input type="radio" name="rating" value="{{ $i }}" id="rating_{{ $i }}">
                                <label for="rating_{{ $i }}">
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
                                </label>
                            </div>
                        @endfor
                    </div>

                    {{-- نوع النتائج --}}
                    <div class="filter-column">
                        <div class="form-section-title">
                            <i class="fas fa-filter"></i>
                            <span>نوع النتائج</span>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="type" value="all" id="type_all" checked>
                            <label for="type_all">الكل</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="type" value="governments" id="type_governments">
                            <label for="type_governments">جهات فقط</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="type" value="services" id="type_services">
                            <label for="type_services">خدمات فقط</label>
                        </div>
                    </div>

                    {{-- ترتيب حسب --}}
                    <div class="filter-column">
                        <div class="form-section-title">
                            <i class="fas fa-sort-amount-down"></i>
                            <span>ترتيب حسب</span>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="sort" value="relevance" id="sort_relevance" checked>
                            <label for="sort_relevance">الأكثر صلة</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="sort" value="newest" id="sort_newest">
                            <label for="sort_newest">الأحدث</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="sort" value="rating" id="sort_rating">
                            <label for="sort_rating">الأعلى تقييماً</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" name="sort" value="most_governments" id="sort_most">
                            <label for="sort_most">الأكثر جهات (للخدمات)</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- زر البحث --}}
            <div class="form-section" style="margin-top: 20px;">
                <button type="button" class="search-btn-advanced" id="searchButton">
                    <i class="fas fa-search"></i>
                    بحث
                </button>
            </div>

            <div class="reset-link">
                <a id="resetFilters">
                    <i class="fas fa-undo-alt me-1"></i>
                    إعادة تعيين جميع الفلاتر
                </a>
            </div>
        </div>

        {{-- نتائج البحث --}}
        <div class="results-section" id="resultsSection" style="display: none;">
            <div class="results-header">
                <div class="results-count">
                    <span>تم العثور على</span>
                    <strong id="resultsCount">0</strong>
                    <span>نتيجة</span>
                </div>
            </div>

            <div class="loading-spinner" id="loadingSpinner">
                <i class="fas fa-spinner"></i>
                <p>جاري البحث...</p>
            </div>

            <div id="resultsContainer"></div>

            <div id="paginationContainer" class="pagination"></div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let isSearching = false;
    let filtersCollapsed = false;

    // التحكم في طي/إظهار الفلاتر
    const filtersWrapper = document.getElementById('filtersWrapper');
    const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
    const toggleFiltersText = document.getElementById('toggleFiltersText');
    const toggleFiltersIcon = document.getElementById('toggleFiltersIcon');

    function toggleFilters() {
        if (filtersCollapsed) {
            // إظهار الفلاتر
            filtersWrapper.classList.remove('collapsed');
            toggleFiltersText.textContent = 'إخفاء الفلاتر المتقدمة';
            toggleFiltersIcon.className = 'fas fa-chevron-up';
            filtersCollapsed = false;
        } else {
            // إخفاء الفلاتر
            filtersWrapper.classList.add('collapsed');
            toggleFiltersText.textContent = 'تغيير إعدادات البحث المتقدم';
            toggleFiltersIcon.className = 'fas fa-chevron-down';
            filtersCollapsed = true;
        }
    }

    if (toggleFiltersBtn) {
        toggleFiltersBtn.addEventListener('click', toggleFilters);
    }

    // الحصول على الفلاتر الحالية
    function getFilters() {
        return {
            query: document.getElementById('searchQuery').value,
            category_id: document.querySelector('input[name="category_id"]:checked')?.value || '',
            rating: document.querySelector('input[name="rating"]:checked')?.value || '',
            type: document.querySelector('input[name="type"]:checked')?.value || 'all',
            sort: document.querySelector('input[name="sort"]:checked')?.value || 'relevance',
            page: currentPage
        };
    }

    // تنفيذ البحث
    async function performSearch() {
        const filters = getFilters();

        // إذا كان حقل البحث فارغاً، لا نبحث
        if (!filters.query.trim()) {
            alert('الرجاء إدخال كلمة البحث');
            return;
        }

        isSearching = true;
        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('resultsContainer').innerHTML = '';
        document.getElementById('resultsSection').style.display = 'block';

        // بعد الضغط على بحث، نخفي الفلاتر تلقائياً (إذا كانت ظاهرة)
        if (!filtersCollapsed) {
            toggleFilters();
        }

        try {
            const params = new URLSearchParams(filters);
            const response = await fetch(`/search/ajax?${params.toString()}`);
            const data = await response.json();

            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('resultsCount').innerText = data.total;

            if (data.results.length === 0) {
                document.getElementById('resultsContainer').innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <p>لا توجد نتائج مطابقة لبحثك</p>
                        <small>جرّب كلمات بحث أخرى أو غير الفلاتر</small>
                    </div>
                `;
                document.getElementById('paginationContainer').innerHTML = '';
                return;
            }

            // عرض النتائج
            let resultsHtml = '';
            data.results.forEach(result => {
                const isGovernment = result.type === 'government';
                const icon = isGovernment ? '<i class="fas fa-building"></i>' : '<i class="fas fa-concierge-bell"></i>';

                resultsHtml += `
                    <div class="result-card" onclick="goToDetails('${result.type}', ${result.id})">
                        <div class="result-header">
                            <div class="result-name">
                                ${icon} ${escapeHtml(result.name)}
                            </div>
                            ${result.rating ? `
                                <div class="result-rating">
                                    <div class="stars">
                                        ${'★'.repeat(Math.floor(result.rating))}${'☆'.repeat(5 - Math.floor(result.rating))}
                                    </div>
                                    <span class="rating-value">${result.rating}</span>
                                    <span class="reviews-count">(${result.reviews_count} تقييم)</span>
                                </div>
                            ` : ''}
                        </div>

                        <div class="result-details">
                            ${result.address ? `<span><i class="fas fa-map-marker-alt"></i> ${escapeHtml(result.address)}</span>` : ''}
                            ${result.work_hours ? `<span><i class="fas fa-clock"></i> ${escapeHtml(result.work_hours)}</span>` : ''}
                            ${result.governments_count ? `<span><i class="fas fa-building"></i> ${result.governments_count} جهة تقدم الخدمة</span>` : ''}
                        </div>

                        <div class="result-badges">
                            ${result.category_name ? `<span class="badge-category"><i class="fas fa-tag"></i> ${escapeHtml(result.category_name)}</span>` : ''}
                            <span class="badge-type">${isGovernment ? 'جهة حكومية' : 'خدمة'}</span>
                        </div>

                        <div class="result-footer">
                            <button class="view-btn" onclick="event.stopPropagation(); goToDetails('${result.type}', ${result.id})">
                                <i class="fas fa-arrow-left"></i> عرض التفاصيل
                            </button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('resultsContainer').innerHTML = resultsHtml;

            // عرض الترقيم
            if (data.last_page > 1) {
                let paginationHtml = '';
                for (let i = 1; i <= data.last_page; i++) {
                    paginationHtml += `
                        <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">
                            ${i}
                        </button>
                    `;
                }
                document.getElementById('paginationContainer').innerHTML = paginationHtml;
            } else {
                document.getElementById('paginationContainer').innerHTML = '';
            }

            // تمرير الصفحة إلى النتائج
            document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });

        } catch (error) {
            console.error('Search error:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('resultsContainer').innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>حدث خطأ أثناء البحث</p>
                    <small>يرجى المحاولة مرة أخرى</small>
                </div>
            `;
        }

        isSearching = false;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function goToDetails(type, id) {
        if (type === 'government') {
            window.location.href = `/governments/${id}`;
        } else {
            window.location.href = `/services/${id}`;
        }
    }

    function goToPage(page) {
        currentPage = page;
        performSearch();
    }

    // إعادة تعيين الفلاتر
    function resetFilters() {
        document.getElementById('searchQuery').value = '';
        document.querySelector('input[name="category_id"][value=""]').checked = true;
        document.querySelector('input[name="rating"][value=""]').checked = true;
        document.querySelector('input[name="type"][value="all"]').checked = true;
        document.querySelector('input[name="sort"][value="relevance"]').checked = true;
        currentPage = 1;
        document.getElementById('resultsSection').style.display = 'none';

        // إذا كانت الفلاتر مطوية، نظهرها
        if (filtersCollapsed) {
            toggleFilters();
        }
    }

    // ربط الأحداث
    document.getElementById('searchButton').addEventListener('click', () => {
        currentPage = 1;
        performSearch();
    });

    document.getElementById('searchQuery').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            currentPage = 1;
            performSearch();
        }
    });

    document.getElementById('resetFilters').addEventListener('click', resetFilters);

    // ملاحظة: تم إزالة حدث change من الفلاتر
    // الفلاتر الآن لا تقوم بالبحث تلقائياً، فقط عند الضغط على زر بحث
</script>
@endsection
