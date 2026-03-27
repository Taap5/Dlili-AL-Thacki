@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container py-5">
    <!-- رأس الصفحة -->
    <div class="category-header mb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="fw-bold mb-2">
                    <i class="fas fa-building text-primary me-2"></i>
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-muted mb-0">{{ $category->description }}</p>
                @endif
            </div>
            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                <i class="fas fa-building me-1"></i>
                {{ $governments->count() }} جهة
            </span>
        </div>
    </div>

    <!-- عرض الجهات -->
    @if($governments->count() > 0)
        <div class="row g-4">
            @foreach($governments as $gov)
                @php
                    $avgRating = $gov->reviews->avg('rating') ?? 0;
                    $reviewsCount = $gov->reviews->count();
                    $images = $gov->images ?? [];
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card government-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <!-- صورة الجهة -->
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage) }}"
                                 class="card-img-top"
                                 alt="{{ $gov->name }}"
                                 style="height: 160px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                <i class="fas fa-building fa-4x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2">
                                <a href="{{ route('governments.show', $gov->id) }}" class="text-decoration-none text-dark">
                                    {{ $gov->name }}
                                </a>
                            </h5>

                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($gov->description ?? 'لا يوجد وصف', 80) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- التقييم -->
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($avgRating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted ms-1">({{ $reviewsCount }})</span>
                                </div>

                                <!-- معلومات الاتصال مختصرة -->
                                @if($gov->contact_number)
                                    <div class="small text-muted">
                                        <i class="fas fa-phone-alt me-1"></i>
                                        {{ Str::limit($gov->contact_number, 12) }}
                                    </div>
                                @endif
                            </div>

                            <!-- ساعات العمل مختصرة -->
                            @if($gov->work_hours)
                                <div class="small text-muted mb-3">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $gov->work_hours }}
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-transparent border-0 pb-4 pt-0">
                            <a href="{{ route('governments.show', $gov->id) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-info-circle me-2"></i>تفاصيل الجهة
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- روابط التصفح (Pagination) -->
        <div class="mt-5">
            {{ $governments->withQueryString()->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد جهات في هذا التصنيف</h5>
                <p class="small text-muted">سيتم إضافة جهات جديدة قريباً</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">العودة للصفحة الرئيسية</a>
            </div>
        </div>
    @endif
</div>

<style>
    .government-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .government-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .category-header {
        border-bottom: 2px solid #eef2f7;
        padding-bottom: 20px;
    }
</style>
@endsection
