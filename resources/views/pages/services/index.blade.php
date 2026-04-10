@extends('layouts.app')

@section('title', 'جميع الخدمات - دليلي الذكي')

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
    .services-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .services-page::before {
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

    .services-page::after {
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

    .services-container {
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

    /* شريط البحث والفلترة */
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

    /* ===== شبكة البطاقات - متساوية الارتفاع ===== */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        align-items: stretch;
    }

    .service-card {
        background: white;
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: relative;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .service-card::before {
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

    .service-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: var(--card-shadow);
    }

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .card-body {
        padding: 1.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        flex: 1;
        height: 100%;
    }

    .service-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .service-card:hover .service-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
        border-radius: 50%;
    }

    .service-icon i {
        font-size: 2rem;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon i {
        color: white;
    }

    .service-icon img {
        width: 45px;
        height: 45px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon img {
        filter: brightness(0) invert(1);
    }

    .service-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        min-height: 2.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .service-title a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .service-title a:hover {
        color: var(--primary);
    }

    .service-description {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.5rem;
    }

    .service-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.2rem;
        min-height: 2.2rem;
    }

    .badge-governments {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-category {
        background: #f0f4ff;
        color: var(--primary);
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
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
        .services-page {
            padding: 1rem 0;
        }

        .services-container {
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

        .services-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .service-icon {
            width: 55px;
            height: 55px;
        }

        .service-icon i {
            font-size: 1.5rem;
        }

        .service-title {
            font-size: 1rem;
            min-height: auto;
        }

        .service-description {
            min-height: auto;
        }
    }

    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }

        .service-badges {
            gap: 0.3rem;
            min-height: auto;
        }

        .badge-governments,
        .badge-category {
            font-size: 0.65rem;
            padding: 0.2rem 0.6rem;
        }
    }
</style>

<div class="services-page">
    <div class="services-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-concierge-bell"></i>
                جميع الخدمات
            </h1>
            <p class="page-subtitle">استعرض جميع الخدمات المتوفرة في منصتنا</p>
        </div>

        <form method="GET" action="{{ route('services.index') }}" class="filters-card">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="ابحث عن خدمة..." value="{{ request('search') }}">
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
            <a href="{{ route('services.index') }}" class="reset-btn">
                <i class="fas fa-undo-alt"></i> إعادة تعيين
            </a>
        </form>

        @if(request('search') || request('category'))
            <div class="search-info">
                @if(request('search'))
                    <i class="fas fa-search"></i> نتائج البحث عن: <strong>"{{ request('search') }}"</strong>
                @endif
                @if(request('category') && !request('search'))
                    <i class="fas fa-filter"></i> عرض خدمات التصنيف المحدد
                @endif
                @if(request('search') && request('category'))
                    <i class="fas fa-filter"></i> مع الفلترة على التصنيف المحدد
                @endif
                - <strong>{{ $services->total() }}</strong> خدمة
            </div>
        @endif

        @if($services->count() > 0)
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-card">
                        <div class="card-body">
                            <div class="service-icon">
                                @if($service->icon_image)
                                    <img src="{{ asset('storage/' . $service->icon_image) }}" alt="{{ $service->name }}">
                                @else
                                    <i class="fas fa-ambulance"></i>
                                @endif
                            </div>

                            <h3 class="service-title">
                                <a href="{{ route('services.show', $service->id) }}">
                                    {{ $service->name }}
                                </a>
                            </h3>

                            <p class="service-description">
                                {{ Str::limit($service->description ?? 'لا يوجد وصف للخدمة', 100) }}
                            </p>

                            <div class="service-badges">
                                <span class="badge-governments">
                                    <i class="fas fa-building"></i>
                                    {{ $service->governments->count() }} جهة
                                </span>
                                @if($service->category)
                                    <span class="badge-category">
                                        <i class="fas fa-tag"></i>
                                        {{ $service->category->name }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('services.show', $service->id) }}" class="btn-details">
                                <i class="fas fa-info-circle me-1"></i> تفاصيل الخدمة
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $services->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h4>لا توجد خدمات</h4>
                <p>
                    @if(request('search'))
                        لا توجد نتائج مطابقة لـ "{{ request('search') }}"
                    @else
                        لم يتم إضافة أي خدمات بعد
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
