@extends('layouts.app')

@section('title', 'المفضلة')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">
        <i class="fas fa-heart text-primary me-2"></i>
        المفضلة
    </h2>

    @if($governments->count() > 0 || $services->count() > 0)
        @if($governments->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3">الجهات الحكومية</h4>
                <div class="row g-4">
                    @foreach($governments as $government)
                        <div class="col-md-6 col-lg-4">
                            <div class="card government-service-card h-100 border-0 shadow-sm rounded-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('governments.show', $government->id) }}" class="text-decoration-none text-dark">
                                            {{ $government->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        {{ Str::limit($government->description ?? 'لا يوجد وصف', 80) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-sm btn-outline-danger favorite-btn"
                                                data-id="{{ $government->id }}"
                                                data-type="government"
                                                data-url="{{ route('favorite.government.toggle') }}">
                                            <i class="fas fa-heart-broken me-1"></i> إزالة
                                        </button>
                                        <a href="{{ route('governments.show', $government->id) }}" class="btn btn-sm btn-outline-primary">
                                            تفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($services->count() > 0)
            <div>
                <h4 class="mb-3">الخدمات</h4>
                <div class="row g-4">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="card government-service-card h-100 border-0 shadow-sm rounded-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('services.show', $service->id) }}" class="text-decoration-none text-dark">
                                            {{ $service->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        {{ Str::limit($service->description ?? 'لا يوجد وصف', 80) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-sm btn-outline-danger favorite-btn"
                                                data-id="{{ $service->id }}"
                                                data-type="service"
                                                data-url="{{ route('favorite.service.toggle') }}">
                                            <i class="fas fa-heart-broken me-1"></i> إزالة
                                        </button>
                                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                            تفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">لا توجد مفضلات</h4>
                <p class="small text-muted">أضف جهات أو خدمات إلى المفضلة لتظهر هنا</p>
                <a href="/" class="btn btn-primary mt-2">استعرض الجهات والخدمات</a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/favorite.js') }}"></script>
@endpush
