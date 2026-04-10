@extends('layouts.app')

@section('title', 'العروض الخاصة')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">
            <i class="fas fa-gift text-primary me-2"></i>
            العروض والمميزات الخاصة
        </h1>
        <p class="text-muted">اكتشف العروض والتخفيضات والخدمات المجانية المقدمة من الجهات الحكومية</p>
    </div>

    {{-- شريط الفلترة --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('offers.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">نوع العرض</label>
                    <select name="type" class="form-select">
                        <option value="">جميع الأنواع</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">الفئة المستهدفة</label>
                    <input type="text" name="audience" class="form-control" placeholder="مثال: كبار السن, مرضى السكر" value="{{ request('audience') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- قائمة العروض --}}
    @if($offers->count() > 0)
        <div class="row g-4">
            @foreach($offers as $offer)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden offer-card-hover">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="offer-icon-lg">
                                    <i class="{{ $offer->icon ?? 'fas fa-tag' }} fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold mb-1">{{ $offer->title }}</h5>
                                    <span class="badge bg-primary mb-2">{{ $types[$offer->offer_type] ?? $offer->offer_type }}</span>
                                </div>
                            </div>

                            <p class="card-text text-muted small">{{ Str::limit($offer->description, 100) }}</p>

                            @if($offer->target_audience)
                                <div class="mb-2">
                                    <i class="fas fa-users text-primary me-1"></i>
                                    <span class="small">{{ $offer->target_audience }}</span>
                                </div>
                            @endif

                            <div class="mb-3">
                                @if($offer->is_permanent)
                                    <span class="badge bg-success">عرض مستمر</span>
                                @else
                                    <span class="badge bg-info">عرض لفترة محدودة</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $offer->government->name }}
                                </div>
                                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    تفاصيل <i class="fas fa-arrow-left ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $offers->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-gift fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">لا توجد عروض حالياً</h4>
            <p class="text-muted">سيتم إضافة العروض الجديدة قريباً</p>
        </div>
    @endif
</div>

<style>
.offer-card-hover {
    transition: transform 0.2s, box-shadow 0.2s;
}
.offer-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1) !important;
}
.offer-icon-lg {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2f3e9e;
}
</style>
@endsection
