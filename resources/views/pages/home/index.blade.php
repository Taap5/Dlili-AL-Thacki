@extends('layouts.app')

@section('title', 'الصفحة الرئيسية - دليلي الذكي')

@section('content')
    <!-- قسم الترحيب -->
    <section class="welcome-section py-4 py-md-5">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h1 class="mb-2 mb-md-3 fw-bold text-primary">مرحباً بك في دليلي الذكي</h1>
                <p class="lead text-muted mb-0">منصة الخدمات الحكومية الموحدة ... بكل سهولة</p>
            </div>

            <!-- شريط البحث الرئيسي -->
            <div class="main-search-section mb-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <h4 class="text-center mb-3 mb-md-4 text-primary">ابحث عن الخدمة التي تحتاجها</h4>

                            <!-- تضمين مكون شريط البحث -->
                            <x-search-bar />

                            <!-- نصائح البحث السريعة -->
                            <div class="search-tips mt-3 mt-md-4">
                                <div class="d-flex flex-wrap justify-content-center gap-2 align-items-center">
                                    <small class="text-muted">جرب البحث عن:</small>

                                    @foreach ($searchSuggestions as $suggestion)
                                        @if ($suggestion['type'] === 'government')
                                            <a href="{{ route('governments.show', $suggestion['id']) }}"
                                                class="badge bg-light text-dark text-decoration-none quick-suggestion"
                                                title="جهة حكومية - {{ $suggestion['name'] }}">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $suggestion['name'] }}
                                            </a>
                                        @elseif($suggestion['type'] === 'service')
                                            <a href="{{ route('services.show', $suggestion['id']) }}"
                                                class="badge bg-light text-dark text-decoration-none quick-suggestion"
                                                title="خدمة - {{ $suggestion['name'] }}">
                                                <i class="fas fa-concierge-bell me-1"></i>
                                                {{ $suggestion['name'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- صندوق الاقتراحات اللحظية -->
                            <div id="suggestionsBox" class="list-group position-absolute d-none" style="z-index:1000;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البطاقات الأربع الرئيسية -->
            <div class="row g-3 g-md-4">
                @foreach ($categories as $category)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="service-card card border-0 shadow-sm h-100 transition-all hover-lift">
                            <div class="card-body text-center p-3 p-md-4">
                                <!-- أيقونة مخصصة لكل تصنيف -->
                                <div class="service-icon mb-2 mb-md-3">
                                    <i class="{{ $category->icon ?? 'fas fa-building' }} fa-2x text-primary"></i>
                                </div>

                                <h5 class="card-title fw-bold mb-2 mb-md-3">{{ $category->name }}</h5>

                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($category->description, 80) }}
                                </p>

                                <!-- عرض عدد الجهات (اختياري) -->
                                <div class="badge bg-light text-dark mb-3">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $category->governments_count ?? $category->governments->count() }} جهة
                                </div>

                                <a href="{{ route('categories.show', $category->id) }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>
                                    استعرض الجهات
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- قسم المميزات -->
    <div class="features-section py-4 py-md-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4 mb-md-5 text-primary">لماذا تختار دليلي الذكي؟</h3>

            <div class="row g-3 g-md-4">
                <div class="col-6 col-md-3">
                    <div class="feature-item text-center p-2 p-md-3">
                        <div class="feature-icon mb-2 mb-md-3">
                            <i class="fas fa-bolt fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1 mb-md-2">سرعة في الإنجاز</h5>
                        <p class="text-muted small">إنجاز المعاملات في أسرع وقت</p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="feature-item text-center p-2 p-md-3">
                        <div class="feature-icon mb-2 mb-md-3">
                            <i class="fas fa-shield fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1 mb-md-2">آمن وموثوق</h5>
                        <p class="text-muted small">حماية بياناتك وتأمين معاملاتك</p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="feature-item text-center p-2 p-md-3">
                        <div class="feature-icon mb-2 mb-md-3">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1 mb-md-2">خدمة 24/7</h5>
                        <p class="text-muted small">خدمات متاحة على مدار الساعة</p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="feature-item text-center p-2 p-md-3">
                        <div class="feature-icon mb-2 mb-md-3">
                            <i class="fas fa-headset fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1 mb-md-2">دعم فني متكامل</h5>
                        <p class="text-muted small">فريق دعم جاهز لمساعدتك</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
