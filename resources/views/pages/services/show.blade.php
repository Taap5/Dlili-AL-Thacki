@extends('layouts.app')

@section('title', $service->name)

@section('content')
@php
    $isFavorited = Auth::check() ? Auth::user()->isServiceFavorite($service->id) : false;
@endphp

<div class="container py-4">
    <!-- بطاقة الخدمة الرئيسية -->
    <div class="card shadow-sm border-0 rounded-4 mb-5 overflow-hidden service-main-card">
        <div class="card-body p-4 p-md-5">
            <!-- أيقونة الخدمة -->
            <div class="service-icon-large mb-4 text-center text-md-start">
                <div class="service-icon-circle mx-auto mx-md-0">
                    <i class="fas fa-concierge-bell fa-3x"></i>
                </div>
            </div>

            <!-- اسم الخدمة مع شريط جانبي وزر المفضلة -->
            <div class="d-flex justify-content-between align-items-start">
                <div class="service-name-wrapper">
                    <h1 class="fw-bold mb-3 service-name">{{ $service->name }}</h1>
                </div>

                @auth
                    <button class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-btn"
                            data-id="{{ $service->id }}"
                            data-type="service"
                            data-url="{{ route('favorite.service.toggle') }}">
                        <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart-broken' }} me-1"></i>
                        <span>{{ $isFavorited ? 'تمت الإضافة' : 'أضف إلى المفضلة' }}</span>
                    </button>
                @endauth
            </div>

            <!-- وصف الخدمة -->
            @if($service->description)
                <div class="service-description mb-4">
                    <p class="text-muted lead">{{ $service->description }}</p>
                </div>
            @endif

            <!-- إحصائيات سريعة -->
            <div class="row g-3 mt-3">
                <div class="col-6 col-md-4">
                    <div class="stat-card text-center">
                        <i class="fas fa-building fa-lg mb-2"></i>
                        <h5 class="mb-0 fw-bold">{{ $service->governments->count() }}</h5>
                        <small class="text-muted">جهة تقدم الخدمة</small>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card text-center">
                        <i class="fas fa-calendar-alt fa-lg mb-2"></i>
                        <h5 class="mb-0 fw-bold">{{ $service->created_at->format('Y') }}</h5>
                        <small class="text-muted">متاحة منذ</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- عنوان قسم الجهات -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-building text-primary me-2"></i>
                الجهات التي تقدم هذه الخدمة
            </h3>
            <p class="text-muted small">اختر الجهة المناسبة لتحصل على الخدمة</p>
        </div>
        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $service->governments->count() }} جهة</span>
    </div>

    <!-- قائمة الجهات - بطاقات منفردة -->
    @if($service->governments->count() > 0)
        <div class="row g-4">
            @foreach($service->governments as $government)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card government-service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <!-- صورة الجهة (إذا وجدت) -->
                        <div class="government-card-img position-relative">
                            @php
                                $govImages = $government->images ?? [];
                                $firstImage = is_array($govImages) && count($govImages) > 0 ? $govImages[0] : null;
                            @endphp
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}"
                                     class="card-img-top"
                                     alt="{{ $government->name }}"
                                     style="height: 160px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 160px;">
                                    <i class="fas fa-building fa-4x text-muted"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-white text-primary rounded-pill shadow-sm px-3 py-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    {{ number_format($government->reviews->avg('rating') ?? 0, 1) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h4 class="card-title fw-bold mb-2">
                                <a href="{{ route('governments.show', $government->id) }}"
                                   class="text-decoration-none text-dark stretched-link">
                                    {{ $government->name }}
                                </a>
                            </h4>

                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($government->description ?? 'لا يوجد وصف', 80) }}
                            </p>

                            <!-- معلومات الاتصال -->
                            <div class="government-contact mb-3">
                                @if($government->phone)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-phone-alt text-primary me-2" style="width: 20px;"></i>
                                        <span class="small">{{ $government->phone }}</span>
                                    </div>
                                @endif
                                @if($government->work_hours)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-primary me-2" style="width: 20px;"></i>
                                        <span class="small">{{ $government->work_hours }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- زر تفاصيل الجهة -->
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('governments.show', $government->id) }}"
                                   class="btn btn-outline-primary rounded-pill">
                                    <i class="fas fa-info-circle me-2"></i>
                                    تفاصيل الجهة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- حالة عدم وجود جهات -->
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد جهات تقدم هذه الخدمة حالياً</h5>
                <p class="small text-muted">سيتم إضافة جهات جديدة قريباً</p>
            </div>
        </div>
    @endif

    <!-- قسم الاقتراحات (خدمات مشابهة) -->
    @if(isset($relatedServices) && $relatedServices->count() > 0)
        <div class="mt-5 pt-3">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-lightbulb text-primary me-2"></i>
                خدمات قد تهمك
            </h4>
            <div class="row g-3">
                @foreach($relatedServices->take(4) as $related)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('services.show', $related->id) }}"
                           class="text-decoration-none">
                            <div class="card related-service-card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                                <i class="fas fa-concierge-bell fa-2x text-primary mb-2"></i>
                                <h6 class="mb-0 text-dark">{{ Str::limit($related->name, 30) }}</h6>
                                <small class="text-muted">{{ $related->governments->count() }} جهة</small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/favorite.js') }}"></script>
@endpush
