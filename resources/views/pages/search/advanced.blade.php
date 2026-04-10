@extends('layouts.app')

@section('title', 'بحث متقدم - دليلي الذكي')

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

    .advanced-search-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .advanced-search-page::before {
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

    .advanced-search-page::after {
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

    /* Search Card */
    .search-card {
        background: white;
        border-radius: 28px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
    }

    .search-title {
        font-size: 1.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .search-title i {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* Search Input */
    .search-input-wrapper {
        margin-bottom: 1.5rem;
    }

    .search-input-large {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 1px solid var(--border-light);
        border-radius: 60px;
        font-size: 1rem;
        transition: all 0.2s;
        background: #fafafa;
    }

    .search-input-large:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    /* Toggle Filters Button */
    .toggle-filters-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.8rem;
        background: #f8fafc;
        border: 1px solid var(--border-light);
        border-radius: 60px;
        color: var(--primary);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
    }

    .toggle-filters-btn:hover {
        background: #f0f4ff;
        border-color: var(--primary);
    }

    /* Filters Wrapper */
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

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-group {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1rem;
    }

    .filter-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    .filter-title i {
        color: var(--primary);
    }

    .filter-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0;
        cursor: pointer;
    }

    .filter-option input {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .filter-option label {
        cursor: pointer;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 0.7rem;
    }

    /* Search Button */
    .search-btn {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 60px;
        padding: 1rem 2rem;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
    }

    .reset-link {
        text-align: center;
        margin-top: 1rem;
    }

    .reset-link a {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .reset-link a:hover {
        color: var(--primary);
    }

    /* Results Section */
    .results-section {
        background: white;
        border-radius: 28px;
        padding: 2rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
        display: none;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .results-count {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .results-count strong {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .loading-spinner {
        text-align: center;
        padding: 3rem;
        display: none;
    }

    .loading-spinner i {
        font-size: 2rem;
        color: var(--primary);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Result Cards */
    .results-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .result-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.2rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border-light);
    }

    .result-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
    }

    .result-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .result-name i {
        color: var(--primary);
    }

    .result-rating {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .stars {
        color: #fbbf24;
        font-size: 0.7rem;
    }

    .rating-value {
        font-weight: 600;
        font-size: 0.85rem;
    }

    .reviews-count {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .result-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 0.8rem;
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .result-details span {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .result-details i {
        color: var(--primary);
    }

    .result-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
    }

    .badge-category {
        background: #f0f4ff;
        color: var(--primary);
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
    }

    .badge-type {
        background: #e0f2fe;
        color: #0369a1;
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
    }

    .result-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 0.8rem;
        border-top: 1px solid var(--border-light);
    }

    .view-btn {
        background: transparent;
        border: 1px solid var(--primary);
        border-radius: 40px;
        padding: 0.3rem 1rem;
        color: var(--primary);
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .view-btn:hover {
        background: var(--primary);
        color: white;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
    }

    .no-results i {
        font-size: 3rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .page-btn {
        padding: 0.4rem 0.8rem;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background: #f0f4ff;
        border-color: var(--primary);
    }

    .page-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-custom {
            padding: 0 1rem;
        }

        .search-card, .results-section {
            padding: 1.2rem;
        }

        .search-title {
            font-size: 1.4rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .result-header {
            flex-direction: column;
        }
    }
</style>

<div class="advanced-search-page">
    <div class="container-custom">
        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span class="separator">/</span>
            <span class="current">بحث متقدم</span>
        </div>

        <!-- Search Card -->
        <div class="search-card">
            <h1 class="search-title">
                <i class="fas fa-sliders-h"></i> بحث متقدم
            </h1>

            <!-- Search Input -->
            <div class="search-input-wrapper">
                <input type="text" id="searchQuery" class="search-input-large" placeholder="اكتب كلمة البحث..." autocomplete="off">
            </div>

            <!-- Toggle Filters Button -->
            <button type="button" class="toggle-filters-btn" id="toggleFiltersBtn">
                <i class="fas fa-sliders-h"></i>
                <span id="toggleFiltersText">إخفاء الفلاتر المتقدمة</span>
                <i class="fas fa-chevron-up" id="toggleFiltersIcon"></i>
            </button>

            <!-- Filters Wrapper -->
            <div id="filtersWrapper" class="filters-wrapper">
                <div class="filter-grid">
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <div class="filter-title">
                            <i class="fas fa-tag"></i> التصنيف
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

                    <!-- Rating Filter -->
                    <div class="filter-group">
                        <div class="filter-title">
                            <i class="fas fa-star"></i> التقييم
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
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </span>
                                    <span>({{ $i }}+ نجوم)</span>
                                </label>
                            </div>
                        @endfor
                    </div>

                    <!-- Type Filter -->
                    <div class="filter-group">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i> نوع النتائج
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

                    <!-- Sort Filter -->
                    <div class="filter-group">
                        <div class="filter-title">
                            <i class="fas fa-sort-amount-down"></i> ترتيب حسب
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

            <!-- Search Button -->
            <button type="button" class="search-btn" id="searchButton">
                <i class="fas fa-search"></i> بحث
            </button>

            <div class="reset-link">
                <a id="resetFilters">
                    <i class="fas fa-undo-alt"></i> إعادة تعيين جميع الفلاتر
                </a>
            </div>
        </div>

        <!-- Results Section -->
        <div class="results-section" id="resultsSection" style="display: none;">
            <div class="results-header">
                <div class="results-count">
                    <span>تم العثور على</span>
                    <strong id="resultsCount">0</strong>
                    <span>نتيجة</span>
                </div>
            </div>

            <div class="loading-spinner" id="loadingSpinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>جاري البحث...</p>
            </div>

            <div id="resultsContainer" class="results-container"></div>

            <div id="paginationContainer" class="pagination"></div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let filtersCollapsed = false;

    const filtersWrapper = document.getElementById('filtersWrapper');
    const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
    const toggleFiltersText = document.getElementById('toggleFiltersText');
    const toggleFiltersIcon = document.getElementById('toggleFiltersIcon');

    function toggleFilters() {
        if (filtersCollapsed) {
            filtersWrapper.classList.remove('collapsed');
            toggleFiltersText.textContent = 'إخفاء الفلاتر المتقدمة';
            toggleFiltersIcon.className = 'fas fa-chevron-up';
            filtersCollapsed = false;
        } else {
            filtersWrapper.classList.add('collapsed');
            toggleFiltersText.textContent = 'تغيير إعدادات البحث المتقدم';
            toggleFiltersIcon.className = 'fas fa-chevron-down';
            filtersCollapsed = true;
        }
    }

    if (toggleFiltersBtn) {
        toggleFiltersBtn.addEventListener('click', toggleFilters);
    }

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

    async function performSearch() {
        const filters = getFilters();

        if (!filters.query.trim()) {
            alert('الرجاء إدخال كلمة البحث');
            return;
        }

        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('resultsContainer').innerHTML = '';
        document.getElementById('resultsSection').style.display = 'block';

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
                        <small class="text-muted">جرّب كلمات بحث أخرى أو غير الفلاتر</small>
                    </div>
                `;
                document.getElementById('paginationContainer').innerHTML = '';
                return;
            }

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
                            ${result.address ? `<span><i class="fas fa-location-dot"></i> ${escapeHtml(result.address)}</span>` : ''}
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

            document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });

        } catch (error) {
            console.error('Search error:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('resultsContainer').innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>حدث خطأ أثناء البحث</p>
                    <small class="text-muted">يرجى المحاولة مرة أخرى</small>
                </div>
            `;
        }
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

    function resetFilters() {
        document.getElementById('searchQuery').value = '';
        document.querySelector('input[name="category_id"][value=""]').checked = true;
        document.querySelector('input[name="rating"][value=""]').checked = true;
        document.querySelector('input[name="type"][value="all"]').checked = true;
        document.querySelector('input[name="sort"][value="relevance"]').checked = true;
        currentPage = 1;
        document.getElementById('resultsSection').style.display = 'none';

        if (filtersCollapsed) {
            toggleFilters();
        }
    }

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
</script>
@endsection
