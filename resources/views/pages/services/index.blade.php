@extends('layouts.app')

@section('title', 'جميع الخدمات')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-concierge-bell text-primary me-2"></i>
            جميع الخدمات
        </h2>

        <!-- فلتر التصنيف -->
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-1"></i>
                @if(request('category'))
                    @php
                        $selectedCat = $categories->where('id', request('category'))->first();
                    @endphp
                    {{ $selectedCat->name ?? 'تصفية حسب التصنيف' }}
                @else
                    تصفية حسب التصنيف
                @endif
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('services.index') }}">الكل</a></li>
                @foreach($categories as $cat)
                    <li><a class="dropdown-item" href="{{ route('services.index', ['category' => $cat->id]) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($services->count() > 0)
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card service-card-item h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body text-center p-4">
                            <!-- أيقونة الخدمة -->
                            <div class="service-icon-wrapper mb-3">
                                <i class="fas fa-concierge-bell fa-3x text-primary"></i>
                            </div>

                            <h5 class="card-title fw-bold mb-2">
                                <a href="{{ route('services.show', $service->id) }}" class="text-decoration-none text-dark">
                                    {{ $service->name }}
                                </a>
                            </h5>

                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($service->description ?? 'لا يوجد وصف', 80) }}
                            </p>

                            <div class="mt-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $service->governments->count() }} جهة
                                </span>
                                @if($service->category)
                                    <span class="badge bg-secondary ms-2">{{ $service->category->name }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                            <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-info-circle me-2"></i>تفاصيل الخدمة
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- روابط التصفح (Pagination) -->
        <div class="mt-5">
            {{ $services->withQueryString()->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-concierge-bell fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد خدمات مسجلة</h5>
                <p class="small text-muted">سيتم إضافة خدمات جديدة قريباً</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">العودة للصفحة الرئيسية</a>
            </div>
        </div>
    @endif
</div>

<style>
    .service-card-item {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .service-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .service-icon-wrapper {
        background: #f8f9ff;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
</style>
@endsection
