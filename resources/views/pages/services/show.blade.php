@extends('layouts.app')

@section('title', $service->name)

@section('content')
    @php

        // حساب إحصائيات الخدمة
        $minPrice = null;
        $minProcessingTime = null;
        $totalRating = 0;
        $ratingCount = 0;

        foreach ($service->governments as $gov) {
            // أقل سعر
            if ($gov->pivot->price) {
                $price = (float) preg_replace('/[^0-9]/', '', $gov->pivot->price);
                if ($minPrice === null || $price < $minPrice) {
                    $minPrice = $price;
                }
            }

            // أقل مدة إنجاز (يمكن تحسين هذا المنطق)
            if ($gov->pivot->processing_time && !$minProcessingTime) {
                $minProcessingTime = $gov->pivot->processing_time;
            }

            // متوسط التقييم
            $avgRating = $gov->reviews->avg('rating');
            if ($avgRating) {
                $totalRating += $avgRating;
                $ratingCount++;
            }
        }
        $avgServiceRating = $ratingCount > 0 ? round($totalRating / $ratingCount, 1) : null;
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

        /* تنسيق الوصف المختصر */
        .short-description {
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }

        .short-description:hover {
            background: rgba(47, 62, 158, 0.05);
            border-radius: 12px;
        }

        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #2f3e9e;
            font-size: 12px;
            font-weight: 500;
            margin-top: 8px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .read-more-btn:hover {
            color: #5a6fc9;
            text-decoration: underline;
        }

        /* مودال عرض الوصف الكامل */
        .description-modal .modal-content {
            border-radius: 24px;
            overflow: hidden;
        }

        .description-modal .modal-body {
            padding: 24px;
            max-height: 70vh;
            overflow-y: auto;
            line-height: 1.8;
            color: #4a5568;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .description-modal .modal-header {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            color: white;
            padding: 16px 24px;
            border: none;
        }

        .description-modal .modal-header .modal-title {
            color: white;
            font-weight: 600;
        }

        .description-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .description-modal .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .description-modal .modal-body {
                padding: 16px;
                font-size: 14px;
            }
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
                    @php
                        $fullDescription = $service->description;
                        $shortDescription = Str::limit($fullDescription, 150, '...');
                        $isLong = Str::length($fullDescription) > 150;
                    @endphp
                    <div class="service-description mb-4 mt-3">
                        <div class="short-description" id="shortDesc">
                            <p class="text-muted" style="white-space: pre-wrap;">{{ $shortDescription }}</p>
                            @if ($isLong)
                                <button class="read-more-btn" onclick="openFullDescription()">
                                    <i class="fas fa-chevron-down"></i> عرض المزيد
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- مودال عرض الوصف الكامل -->
                    <div class="modal fade description-modal" id="fullDescriptionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-align-right me-2"></i>
                                        وصف {{ $service->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p style="white-space: pre-wrap; line-height: 1.8;">{{ $fullDescription }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- معرض الصور -->
                @if (count($images) > 0)
                    <div class="mb-4 images-strip-container">
                        <div class="d-flex gap-2 overflow-auto py-2 images-strip">
                            @foreach ($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="rounded-3 shadow-sm service-thumb"
                                    style="width: 100px; height: 75px; object-fit: cover; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                    data-full-img="{{ asset('storage/' . $img) }}" alt="صورة الخدمة">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- إحصائيات سريعة -->
                <!-- إحصائيات سريعة محسنة -->
                <div class="row g-3 mt-3">
                    <div class="col-6 col-md-3">
                        <div class="stat-card text-center">
                            <i class="fas fa-building fa-lg mb-2 text-primary"></i>
                            <h5 class="mb-0 fw-bold">{{ $service->governments->count() }}</h5>
                            <small class="text-muted">جهة تقدم الخدمة</small>
                        </div>
                    </div>

                    @if ($minPrice)
                        <div class="col-6 col-md-3">
                            <div class="stat-card text-center">
                                <i class="fas fa-tag fa-lg mb-2 text-success"></i>
                                <h5 class="mb-0 fw-bold">{{ number_format($minPrice) }} ريال</h5>
                                <small class="text-muted">أقل سعر</small>
                            </div>
                        </div>
                    @endif

                    @if ($minProcessingTime)
                        <div class="col-6 col-md-3">
                            <div class="stat-card text-center">
                                <i class="fas fa-hourglass-half fa-lg mb-2 text-info"></i>
                                <h5 class="mb-0 fw-bold">{{ $minProcessingTime }}</h5>
                                <small class="text-muted">أقل مدة إنجاز</small>
                            </div>
                        </div>
                    @endif

                    @if ($avgServiceRating)
                        <div class="col-6 col-md-3">
                            <div class="stat-card text-center">
                                <i class="fas fa-star fa-lg mb-2 text-warning"></i>
                                <h5 class="mb-0 fw-bold">{{ $avgServiceRating }}</h5>
                                <small class="text-muted">متوسط التقييم</small>
                            </div>
                        </div>
                    @endif
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
                        $uniqueId = 'gov_service_' . $government->id . '_' . $service->id;
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
                                <!-- رأس البطاقة مع زر طي/فتح -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="card-title fw-bold mb-0">
                                        <a href="{{ route('governments.show', $government->id) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $government->name }}
                                        </a>
                                    </h4>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary rounded-pill toggle-details-btn"
                                        onclick="toggleGovernmentDetails('{{ $uniqueId }}')"
                                        style="font-size: 11px; padding: 4px 10px;">
                                        <i class="fas fa-chevron-down" id="icon_{{ $uniqueId }}"></i>
                                        <span id="text_{{ $uniqueId }}">عرض التفاصيل</span>
                                    </button>
                                </div>

                                <!-- معلومات سريعة (تظهر دائماً) -->
<!-- معلومات سريعة (تظهر دائماً) -->
<div class="quick-info mb-2">
    @php
        $userLat = Auth::check() ? Auth::user()->location_lat ?? null : null;
        $userLng = Auth::check() ? Auth::user()->location_long ?? null : null;
        $distance = null;

        // حساب المسافة إذا توفر موقع المستخدم وموقع الجهة
        if ($userLat && $userLng && $government->location_lat && $government->location_long) {
            // دالة حساب المسافة (يمكنك إضافتها في helper أو هنا)
            $earthRadius = 6371; // km
            $latDelta = deg2rad($government->location_lat - $userLat);
            $lonDelta = deg2rad($government->location_long - $userLng);
            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos(deg2rad($userLat)) * cos(deg2rad($government->location_lat)) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = round($earthRadius * $c, 1);
        }
    @endphp

    @if ($pivot->price)
        <span class="badge bg-success me-1">{{ $pivot->price }}</span>
    @endif

    @if ($pivot->processing_time)
        <span class="badge bg-info me-1">
            <i class="fas fa-hourglass-half"></i> {{ $pivot->processing_time }}
        </span>
    @endif

    @if ($distance)
        <span class="badge bg-secondary me-1">
            <i class="fas fa-location-dot"></i> {{ $distance }} كم
        </span>
    @endif
</div>
                                <!-- التفاصيل الكاملة (تظهر عند الضغط) -->
                                <div class="service-full-details" id="details_{{ $uniqueId }}"
                                    style="display: none;">
                                    @if ($pivot->description)
                                        <div class="pivot-description mb-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $pivot->description }}
                                        </div>
                                    @endif

                                    <div class="service-detail-badge">
                                        <div class="row g-2">
                                            {{-- مدة الإنجاز --}}
                                            @if ($pivot->processing_time)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-hourglass-half me-1"></i> ⏱️ مدة الإنجاز
                                                    </span>
                                                    <div class="service-detail-value">
                                                        {{ $pivot->processing_time }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- موقع التقديم --}}
                                            @if ($pivot->office_location)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-door-open me-1"></i> 📍 موقع التقديم
                                                    </span>
                                                    <div class="service-detail-value">
                                                        {{ $pivot->office_location }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- الأوراق المطلوبة --}}
                                            @if ($pivot->required_documents)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-file-alt me-1"></i> 📄 الأوراق المطلوبة
                                                    </span>
                                                    <div class="service-detail-value"
                                                        style="white-space: pre-line; font-size: 12px;">
                                                        {{ $pivot->required_documents }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- الإجراءات خطوة بخطوة --}}
                                            @if ($pivot->steps)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-list-ol me-1"></i> 📋 الإجراءات
                                                    </span>
                                                    <div class="service-detail-value"
                                                        style="white-space: pre-line; font-size: 12px;">
                                                        {{ $pivot->steps }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- الشروط --}}
                                            @if ($pivot->conditions)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-clipboard-list me-1"></i> 📜 الشروط
                                                    </span>
                                                    <div class="service-detail-value"
                                                        style="white-space: pre-line; font-size: 12px;">
                                                        {{ $pivot->conditions }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- ملاحظات إضافية --}}
                                            @if ($pivot->notes)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-pen-alt me-1"></i> 📝 ملاحظات
                                                    </span>
                                                    <div class="service-detail-value"
                                                        style="white-space: pre-line; font-size: 12px;">
                                                        {{ $pivot->notes }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- رقم الاتصال الخاص --}}
                                            @if ($pivot->contact_number)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-phone-alt me-1"></i> 📞 رقم الاتصال الخاص
                                                    </span>
                                                    <div class="service-detail-value">
                                                        <a href="tel:{{ $pivot->contact_number }}"
                                                            class="text-decoration-none">
                                                            {{ $pivot->contact_number }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- ساعات العمل --}}
                                            @if ($pivot->work_hours)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-clock me-1"></i> 🕐 ساعات العمل
                                                    </span>
                                                    <div class="service-detail-value">
                                                        {{ $pivot->work_hours }}
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- السعر --}}
                                            @if ($pivot->price)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-coins me-1"></i> 💰 الرسوم
                                                    </span>
                                                    <div class="service-detail-value">
                                                        <span class="price-badge">
                                                            {{ $pivot->price }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- يتطلب حجز مسبق --}}
                                            @if ($pivot->requires_appointment)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-calendar-check me-1"></i> 📅 يتطلب حجز مسبق
                                                    </span>
                                                    <div class="service-detail-value">
                                                        نعم
                                                        @if ($pivot->appointment_phone)
                                                            - <a
                                                                href="tel:{{ $pivot->appointment_phone }}">{{ $pivot->appointment_phone }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- حقول خاصة بالمستشفيات --}}
                                            @if ($pivot->doctor_specialist)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-user-md me-1"></i> 👨‍⚕️ الطبيب المختص
                                                    </span>
                                                    <div class="service-detail-value">
                                                        {{ $pivot->doctor_specialist }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($pivot->hospital_stay_duration)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-procedures me-1"></i> 🏥 مدة التنويم
                                                    </span>
                                                    <div class="service-detail-value">
                                                        {{ $pivot->hospital_stay_duration }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($pivot->emergency_notes)
                                                <div class="col-12">
                                                    <span class="service-detail-label">
                                                        <i class="fas fa-ambulance me-1"></i> 🚨 ملاحظات للطوارئ
                                                    </span>
                                                    <div class="service-detail-value"
                                                        style="white-space: pre-line; font-size: 12px;">
                                                        {{ $pivot->emergency_notes }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات الاتصال العامة للجهة (تظهر دائماً) -->
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
                                        <img src="{{ asset('storage/' . $related->icon_image) }}"
                                            alt="{{ $related->name }}"
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

        // دالة فتح مودال عرض الوصف الكامل
        function openFullDescription() {
            const modalElement = document.getElementById('fullDescriptionModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }

        function toggleGovernmentDetails(id) {
            const details = document.getElementById(`details_${id}`);
            const icon = document.getElementById(`icon_${id}`);
            const text = document.getElementById(`text_${id}`);

            if (details.style.display === 'none') {
                details.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                text.innerText = 'إخفاء التفاصيل';
            } else {
                details.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                text.innerText = 'عرض التفاصيل';
            }
        }
    </script>
@endpush
