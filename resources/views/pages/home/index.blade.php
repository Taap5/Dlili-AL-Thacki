@extends('layouts.app')

@section('title', 'الصفحة الرئيسية - دليلي الذكي')

@section('content')
<style>
    /* ===== ألوان الموقع ===== */
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --secondary: #ffc107;
        --bg-light: #fef9f0;
        --bg-gray: #f8f9fa;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
    }

    /* ===== قسم الترحيب ===== */
    .welcome-section {
        background: linear-gradient(135deg, #ffffff 0%, #fef9f0 50%, #f0f4ff 100%);
        padding: 3rem 0 4rem;
        position: relative;
        overflow: hidden;
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 80%;
        height: 150%;
        background: radial-gradient(circle, rgba(47,62,158,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -20%;
        width: 60%;
        height: 100%;
        background: radial-gradient(circle, rgba(90,111,201,0.02) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-section .container {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    .hero-text {
        text-align: center;
        margin-bottom: 2rem;
    }

    .hero-text h1 {
        font-size: 2.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        animation: fadeInUp 0.6s ease-out;
    }

    .hero-text .lead {
        font-size: 1.2rem;
        color: var(--text-muted);
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== قسم البحث ===== */
    .main-search-section {
        padding: 1rem 0;
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .main-search-section h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
        text-align: center;
        width: 100%;
    }

    .main-search-section h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    /* ===== حاوية شريط البحث ===== */
    .search-wrapper {
        max-width: 700px;
        margin: 0 auto;
    }

    /* ===== نصائح البحث السريعة ===== */
    .search-tips {
        max-width: 700px;
        margin: 1.5rem auto 0;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50px;
        padding: 12px 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
    }

    .search-tips small {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .search-tips .quick-suggestion {
        background: #f0f4ff !important;
        color: var(--primary) !important;
        padding: 8px 16px !important;
        border-radius: 30px !important;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .search-tips .quick-suggestion:hover {
        background: var(--primary) !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.2);
    }

    /* ===== بطاقات التصنيفات ===== */
    .services-section {
        padding: 2rem 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .section-title h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .section-title h2::after {
        content: '';
        position: absolute;
        bottom: -12px;
        right: 50%;
        transform: translateX(50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    .section-title p {
        color: var(--text-muted);
        font-size: 1rem;
        margin-top: 1rem;
    }

    .service-card {
        background: #ffffff;
        border-radius: 24px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(47, 62, 158, 0.08) !important;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.25) !important;
        border-color: transparent !important;
    }

    .service-card:hover::before {
        opacity: 0.03;
    }

    .service-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e8eaf6, #f0f4ff);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
    }

    .service-card:hover .service-icon i {
        color: white !important;
    }

    .service-icon i {
        font-size: 2.5rem;
        transition: all 0.3s ease;
    }

    .service-card .card-body {
        text-align: center;
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
        font-size: 1.25rem;
    }

    .service-card:hover .card-title {
        color: var(--primary);
    }

    .card-text {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .badge-count {
        background: #f0f4ff;
        color: var(--primary);
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 1rem;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }

    .service-card .btn {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: auto;
    }

    .service-card .btn:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.2);
    }

    /* ===== قسم المميزات ===== */
    .features-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #fef9f0 100%);
        padding: 4rem 0;
        margin-top: 2rem;
        position: relative;
    }

    .features-section .section-title h2 {
        color: var(--primary);
    }

    .feature-item {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(47, 62, 158, 0.1);
        height: 100%;
        text-align: center;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: 0 10px 25px -8px rgba(47, 62, 158, 0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #e8eaf6, #f0f4ff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .feature-item:hover .feature-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
    }

    .feature-item:hover .feature-icon i {
        color: white !important;
    }

    .feature-icon i {
        font-size: 2rem;
        transition: all 0.3s ease;
    }

    .feature-item h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .feature-item p {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* ===== استجابة للهواتف ===== */
    @media (max-width: 768px) {
        .welcome-section {
            min-height: auto;
            padding: 2rem 0 2.5rem;
        }

        .hero-text h1 {
            font-size: 1.8rem;
        }

        .hero-text .lead {
            font-size: 0.95rem;
        }

        .main-search-section h4 {
            font-size: 1.1rem;
        }

        .search-wrapper {
            max-width: 100%;
            padding: 0 15px;
        }

        .search-tips {
            max-width: 100%;
            margin: 1rem 15px 0;
            padding: 10px 16px;
            border-radius: 30px;
        }

        .search-tips .quick-suggestion {
            padding: 5px 12px !important;
            font-size: 11px;
        }

        .section-title h2 {
            font-size: 1.5rem;
        }

        .section-title p {
            font-size: 0.85rem;
        }

        .service-icon {
            width: 60px;
            height: 60px;
        }

        .service-icon i {
            font-size: 1.8rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .card-text {
            font-size: 0.8rem;
        }

        .badge-count {
            font-size: 10px;
            padding: 4px 10px;
        }

        .service-card .btn {
            font-size: 12px;
            padding: 6px 12px;
        }

        .features-section {
            padding: 2rem 0;
        }

        .feature-item {
            padding: 1rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
        }

        .feature-icon i {
            font-size: 1.5rem;
        }

        .feature-item h5 {
            font-size: 0.9rem;
        }

        .feature-item p {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .hero-text h1 {
            font-size: 1.4rem;
        }

        .hero-text .lead {
            font-size: 0.85rem;
        }

        .main-search-section h4 {
            font-size: 1rem;
        }

        .search-tips small {
            font-size: 0.75rem;
        }
    }
</style>

<!-- قسم الترحيب -->
<section class="welcome-section">
    <div class="container">
        <div class="hero-text">
            <h1>مرحباً بك في دليلي الذكي</h1>
            <p class="lead">منصة الخدمات الحكومية الموحدة ... بكل سهولة</p>
        </div>

        <!-- شريط البحث الرئيسي -->
        <div class="main-search-section">
            <h4>ابحث عن الخدمة التي تحتاجها</h4>

            <div class="search-wrapper">
                <!-- تضمين مكون شريط البحث -->
                <x-search-bar />

                <!-- نصائح البحث السريعة -->
                <div class="search-tips">
                    <small class="text-muted">جرب البحث عن:</small>

                    @foreach ($searchSuggestions as $suggestion)
                        @if ($suggestion['type'] === 'government')
                            <a href="{{ route('governments.show', $suggestion['id']) }}"
                                class="badge quick-suggestion"
                                title="جهة حكومية - {{ $suggestion['name'] }}">
                                <i class="fas fa-building me-1"></i>
                                {{ $suggestion['name'] }}
                            </a>
                        @elseif($suggestion['type'] === 'service')
                            <a href="{{ route('services.show', $suggestion['id']) }}"
                                class="badge quick-suggestion"
                                title="خدمة - {{ $suggestion['name'] }}">
                                <i class="fas fa-concierge-bell me-1"></i>
                                {{ $suggestion['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- بطاقات التصنيفات -->
        <div class="services-section">
            <div class="section-title">
                <h2>التصنيفات الرئيسية</h2>
                <p>اختر التصنيف المناسب لتبدأ رحلتك</p>
            </div>

            <div class="row g-3 g-md-4">
                @foreach ($categories as $category)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="service-card card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-3 p-md-4">
                                <!-- أيقونة مخصصة لكل تصنيف -->
                                <div class="service-icon mb-2 mb-md-3">
                                    <i class="{{ $category->icon ?? 'fas fa-building' }} text-primary"></i>
                                </div>

                                <h5 class="card-title fw-bold mb-2 mb-md-3">{{ $category->name }}</h5>

                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($category->description, 80) }}
                                </p>

                                <!-- عرض عدد الجهات -->
                                <div class="badge-count">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $category->governments_count ?? $category->governments->count() }} جهة
                                </div>

                                <a href="{{ route('categories.show', $category->id) }}"
                                    class="btn">
                                    <i class="fas fa-eye me-1"></i>
                                    استعرض الجهات
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- قسم المميزات -->
<div class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>لماذا تختار دليلي الذكي؟</h2>
            <p>نقدم لك تجربة فريدة ومميزة</p>
        </div>

        <div class="row g-3 g-md-4">
            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bolt text-primary"></i>
                    </div>
                    <h5>سرعة في الإنجاز</h5>
                    <p>إنجاز المعاملات في أسرع وقت</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield text-primary"></i>
                    </div>
                    <h5>آمن وموثوق</h5>
                    <p>حماية بياناتك وتأمين معاملاتك</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock text-primary"></i>
                    </div>
                    <h5>خدمة 24/7</h5>
                    <p>خدمات متاحة على مدار الساعة</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset text-primary"></i>
                    </div>
                    <h5>دعم فني متكامل</h5>
                    <p>فريق دعم جاهز لمساعدتك</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- زر الطوارئ والمودال -->
@include('components.emergency-button')
@include('components.emergency-modal')
@push('scripts')
<script src="{{ asset('js/emergency.js') }}"></script>
@endpush
@endsection
