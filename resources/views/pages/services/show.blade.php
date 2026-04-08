@extends('layouts.app')

@section('title', $service->name)

@section('content')
    @php
        $isFavorited = Auth::check() ? Auth::user()->isServiceFavorite($service->id) : false;
        $images = $service->images ?? [];
    @endphp

    <style>
        /* تنسيقات إضافية */
        .government-service-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .government-service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .service-detail-badge {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-top: 12px;
        }

        .service-detail-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
            display: block;
        }

        .service-detail-value {
            font-size: 14px;
            font-weight: 500;
            color: #2f3e9e;
        }

        .pivot-description {
            background: #f0f4ff;
            border-radius: 12px;
            padding: 12px;
            margin: 12px 0;
            font-size: 13px;
            line-height: 1.5;
            color: #1e2a6e;
        }

        .price-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
            margin: 12px 0;
        }

        .service-icon-circle {
            width: 100px;
            height: 100px;
            background: #f0f4ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
    </style>

    <div class="container py-4">
        <!-- بطاقة الخدمة الرئيسية -->
        <div class="card shadow-sm border-0 rounded-4 mb-5 overflow-hidden service-main-card">
            <div class="card-body p-4 p-md-5">
                <!-- أيقونة الخدمة -->
                <div class="service-icon-circle mx-auto mx-md-0">
                    @if ($service->icon_image)
                        <img src="{{ asset('storage/' . $service->icon_image) }}" alt="{{ $service->name }}"
                            style="width: 80px; height: 80px; object-fit: contain;">
                    @else
                        <i class="fas fa-ambulance fa-3x text-primary"></i>
                    @endif
                </div>

                <!-- اسم الخدمة مع شريط جانبي وزر المفضلة -->
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="service-name-wrapper">
                        <h1 class="fw-bold mb-3 service-name">{{ $service->name }}</h1>
                        @if ($service->category)
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="fas fa-tag me-1"></i> {{ $service->category->name }}
                            </span>
                        @endif
                    </div>

                    @auth
                        <button class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-btn"
                            data-id="{{ $service->id }}" data-type="service" data-url="{{ route('favorite.service.toggle') }}">
                            <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart-broken' }} me-1"></i>
                            <span>{{ $isFavorited ? 'تمت الإضافة' : 'أضف إلى المفضلة' }}</span>
                        </button>
                    @endauth
                </div>

                <!-- وصف الخدمة العام -->
                @if ($service->description)
                    <div class="service-description mb-4 mt-3">
                       <p class="text-muted lead" style="white-space: pre-wrap;">{{ $service->description }}</p>
                    </div>
                @endif

                <!-- معرض الصور -->
                @if (count($images) > 0)
                    <div class="mb-4 images-strip-container">
                        <div class="d-flex gap-2 overflow-auto py-2 images-strip">
                            @foreach ($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="rounded-3 shadow-sm service-thumb"
                                    style="width: 100px; height: 75px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#imagePreviewModal" data-full-img="{{ asset('storage/' . $img) }}"
                                    alt="صورة الخدمة">
                            @endforeach
                        </div>
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
                <p class="text-muted small">اختر الجهة المناسبة لتحصل على الخدمة - تفاصيل الخدمة تختلف حسب الجهة</p>
            </div>
            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $service->governments->count() }} جهة</span>
        </div>

        <!-- قائمة الجهات - بطاقات منفردة مع تفاصيل الخدمة لكل جهة -->
        @if ($service->governments->count() > 0)
            <div class="row g-4">
                @foreach ($service->governments as $government)
                    @php
                        $pivot = $government->pivot;
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card government-service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <!-- صورة الجهة (إذا وجدت) -->
                            <div class="government-card-img position-relative">
                                @php
                                    $govImages = $government->images ?? [];
                                    $firstImage = is_array($govImages) && count($govImages) > 0 ? $govImages[0] : null;
                                @endphp
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}" class="card-img-top"
                                        alt="{{ $government->name }}" style="height: 160px; object-fit: cover;">
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

                                <!-- تفاصيل الخدمة الخاصة بهذه الجهة (من pivot) -->
                                @if ($pivot)
                                    @if ($pivot->description)
                                        <div class="pivot-description">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $pivot->description }}
                                        </div>
                                    @endif

                                    <div class="service-detail-badge">
                                        <div class="row g-2">
                                            @if ($pivot->contact_number)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-phone-alt me-1"></i> رقم الاتصال الخاص
                                                    </span>
                                                    <div class="service-detail-value">
                                                        <a href="tel:{{ $pivot->contact_number }}"
                                                            class="text-decoration-none">
                                                            {{ $pivot->contact_number }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($pivot->work_hours)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-clock me-1"></i> ساعات العمل لهذه الخدمة
                                                    </span>
                                                    <div class="service-detail-value">
                                                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                                                        {{ $pivot->work_hours }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($pivot->price)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-coins me-1"></i> الرسوم
                                                    </span>
                                                    <div class="service-detail-value">
                                                        <span class="price-badge">
                                                            <i class="fas fa-wallet me-1"></i> {{ $pivot->price }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- معلومات الاتصال العامة للجهة -->
                                <div class="government-contact mt-3 pt-2">
                                    <div class="divider"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt me-1"></i> هاتف الجهة:
                                        </small>
                                        <small class="fw-bold">
                                            {{ $government->contact_number ?? 'غير متوفر' }}
                                        </small>
                                    </div>
                                    @if ($government->address)
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i> العنوان:
                                            </small>
                                            <small class="fw-bold text-truncate" style="max-width: 150px;">
                                                {{ Str::limit($government->address, 30) }}
                                            </small>
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
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد جهات تقدم هذه الخدمة حالياً</h5>
                    <p class="small text-muted">سيتم إضافة جهات جديدة قريباً</p>
                </div>
            </div>
        @endif

        <!-- قسم الاقتراحات (خدمات مشابهة) -->
        @if (isset($relatedServices) && $relatedServices->count() > 0)
            <div class="mt-5 pt-3">
                <h4 class="fw-bold mb-4">
                    <i class="fas fa-lightbulb text-primary me-2"></i>
                    خدمات قد تهمك
                </h4>
                <div class="row g-3">
                    @foreach ($relatedServices->take(4) as $related)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('services.show', $related->id) }}" class="text-decoration-none">
                                <div class="card related-service-card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                                    @if ($related->icon_image)
                                        <img src="{{ asset('storage/' . $related->icon_image) }}" alt="{{ $related->name }}"
                                            style="width: 50px; height: 50px; object-fit: contain; margin-bottom: 8px;">
                                    @else
                                        <i class="fas fa-ambulance fa-2x text-primary mb-2"></i>
                                    @endif
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

    <!-- Modal عرض الصور -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img id="previewImage" src="" class="img-fluid rounded-4"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/favorite.js') }}"></script>
    <script>
        document.querySelectorAll('.service-thumb').forEach(img => {
            img.addEventListener('click', function() {
                const previewImage = document.getElementById('previewImage');
                if (previewImage) {
                    previewImage.src = this.dataset.fullImg || this.src;
                }
            });
        });
    </script>
@endpush
