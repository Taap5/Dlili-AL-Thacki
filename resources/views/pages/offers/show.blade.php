@extends('layouts.app')

@section('title', $offer->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offers.index') }}">العروض</a></li>
                    <li class="breadcrumb-item active">{{ $offer->title }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="offer-icon-display mx-auto mb-3">
                            <i class="{{ $offer->icon ?? 'fas fa-gift' }} fa-3x"></i>
                        </div>
                        <h1 class="fw-bold mb-2">{{ $offer->title }}</h1>
                        <span class="badge bg-primary px-3 py-2">{{ $types[$offer->offer_type] ?? $offer->offer_type }}</span>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle text-primary me-2"></i> وصف العرض</h5>
                        <p class="text-muted">{{ $offer->description }}</p>
                    </div>

                    @if($offer->target_audience)
                    <div class="mb-4">
                        <h5><i class="fas fa-users text-primary me-2"></i> الفئة المستهدفة</h5>
                        <p class="text-muted">{{ $offer->target_audience }}</p>
                    </div>
                    @endif

                    @if($offer->terms)
                    <div class="mb-4">
                        <h5><i class="fas fa-file-contract text-primary me-2"></i> الشروط والأحكام</h5>
                        <p class="text-muted">{{ $offer->terms }}</p>
                    </div>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="info-card">
                                <i class="fas fa-building"></i>
                                <div>
                                    <div class="small text-muted">الجهة المقدمة</div>
                                    <div class="fw-bold">{{ $offer->government->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <div class="small text-muted">مدة العرض</div>
                                    <div class="fw-bold">
                                        @if($offer->is_permanent)
                                            عرض مستمر
                                        @else
                                            {{ $offer->start_date ?: 'غير محدد' }} - {{ $offer->end_date ?: 'غير محدد' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($offer->contact_number)
                    <div class="mb-4">
                        <a href="tel:{{ $offer->contact_number }}" class="btn btn-success w-100">
                            <i class="fas fa-phone-alt me-2"></i> استفسار: {{ $offer->contact_number }}
                        </a>
                    </div>
                    @endif

                    <div class="d-flex gap-3">
                        <a href="{{ route('governments.show', $offer->government->id) }}" class="btn btn-outline-primary flex-grow-1">
                            <i class="fas fa-building me-1"></i> زيارة صفحة الجهة
                        </a>
                        <a href="{{ route('offers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-1"></i> جميع العروض
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.offer-icon-display {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f3e9e;
}
.info-card {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.info-card i {
    font-size: 24px;
    color: #2f3e9e;
}
</style>
@endsection
