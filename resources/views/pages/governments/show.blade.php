@extends('layouts.app')

@section('title', $government->name)

@section('content')
    <!-- بداية التصحيح - عرض أخطاء PHP -->
    @php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    @endphp
    <!-- نهاية التصحيح -->
    @php
        $images = $government->images ?? [];
        $isFavorited = Auth::check() ? Auth::user()->isGovernmentFavorite($government->id) : false;

        // ===== دالة تجميع الخدمات حسب الاسم =====
        $groupedServices = [];
        $standaloneServices = [];

        foreach ($government->services as $service) {
            $serviceName = $service->name;

            // هل يحتوي الاسم على "-" ؟
            if (str_contains($serviceName, '-')) {
                $parts = explode('-', $serviceName, 2);
                $groupName = trim($parts[0]);
                $subName = trim($parts[1]);

                if (!isset($groupedServices[$groupName])) {
                    $groupedServices[$groupName] = [];
                }
                $groupedServices[$groupName][] = (object) [
                    'display_name' => $subName,
                    'original_name' => $serviceName,
                    'service_id' => $service->id,
                    'description' => $service->pivot->description ?? $service->description,
                    'contact_number' => $service->pivot->contact_number,
                    'work_hours' => $service->pivot->work_hours,
                    'price' => $service->pivot->price,
                    'icon_image' => $service->icon_image,
                    // الحقول الجديدة
                    'processing_time' => $service->pivot->processing_time ?? '',
                    'office_location' => $service->pivot->office_location ?? '',
                    'required_documents' => $service->pivot->required_documents ?? '',
                    'steps' => $service->pivot->steps ?? '',
                    'conditions' => $service->pivot->conditions ?? '',
                    'notes' => $service->pivot->notes ?? '',
                    'requires_appointment' => $service->pivot->requires_appointment ?? false,
                    'appointment_phone' => $service->pivot->appointment_phone ?? '',
                    'doctor_specialist' => $service->pivot->doctor_specialist ?? '',
                    'hospital_stay_duration' => $service->pivot->hospital_stay_duration ?? '',
                    'emergency_notes' => $service->pivot->emergency_notes ?? '',
                ];
            } else {
                $standaloneServices[] = $service;
            }
        }

        // ترتيب المجموعات أبجدياً
        ksort($groupedServices);
    @endphp
    <div class="container py-4">
        <!-- بطاقة الجهة الرئيسية -->
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden main-government-card">
            <div class="card-top-bar" style="height: 6px; background: linear-gradient(90deg, #2f3e9e, #5a6fc9, #8b9eff);">
            </div>
            <div class="card-body p-4 p-md-5" style="background: linear-gradient(135deg, #ffffff 0%, #fef9f0 100%);">
                <!-- رأس الجهة مع زر المفضلة -->
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="government-name-wrapper">
                        <h1 class="fw-bold mb-2 government-name">{{ $government->name }}</h1>
                        @if ($government->reviews_count > 0)
                            <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                                <div class="rating-badge">
                                    <span class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($government->reviews_avg_rating))
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span
                                        class="rating-value fw-bold ms-1">{{ number_format($government->reviews_avg_rating, 1) }}</span>
                                    <span class="text-muted ms-1">({{ $government->reviews_count }} تقييم)</span>
                                </div>
                            </div>
                        @else
                            <div class="text-muted mt-2">
                                <i class="far fa-star text-warning me-1"></i>
                                <small>لا توجد تقييمات بعد - كن أول من يقيم!</small>
                            </div>
                        @endif
                    </div>
                    @auth
                        <button class="btn favorite-btn {{ $isFavorited ? 'btn-favorited' : 'btn-outline-favorite' }}"
                            data-id="{{ $government->id }}" data-type="government"
                            data-url="{{ route('favorite.government.toggle') }}">
                            <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart' }} me-2"></i>
                            <span>{{ $isFavorited ? 'تمت الإضافة إلى المفضلة' : 'أضف إلى المفضلة' }}</span>
                        </button>
                    @endauth
                </div>
                @if ($government->address)
                    <div class="address-info mt-4 d-flex align-items-center gap-2"
                        style="background: #f8f9fa; padding: 12px 16px; border-radius: 12px;">
                        <div class="address-icon"><i class="fas fa-location-dot text-primary fa-lg"></i></div>
                        <span class="text-secondary">{{ $government->address }}</span>
                    </div>
                @endif
                {{-- حالة الدوام وساعات العمل --}}
                @php
                    $isOpen = $government->isOpen();
                    $formattedHours = $government->getFormattedWorkHours();
                @endphp
                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                    <div
                        class="status-badge {{ $isOpen === true ? 'status-open' : ($isOpen === false ? 'status-closed' : 'status-unknown') }}">
                        <i
                            class="fas {{ $isOpen === true ? 'fa-door-open' : ($isOpen === false ? 'fa-clock' : 'fa-question') }}"></i>
                        <span>
                            @if ($isOpen === true)
                                مفتوح الآن
                            @elseif($isOpen === false)
                                مغلق
                            @else
                                غير معروف
                            @endif
                        </span>
                    </div>
                    @if ($formattedHours)
                        <span class="text-muted small work-hours-text" title="{{ $formattedHours }}">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ Str::limit($formattedHours, 50) }}
                            @if (strlen($formattedHours) > 50)
                                <button class="btn btn-link btn-sm p-0 ms-1" onclick="showFullWorkHours()"
                                    style="font-size: 11px;">عرض الكل</button>
                            @endif
                        </span>
                    @endif
                </div>

                {{-- مودال عرض ساعات العمل الكاملة --}}
                <div class="modal fade" id="workHoursModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg, #2f3e9e, #5a6fc9); color: white;">
                                <h5 class="modal-title">
                                    <i class="fas fa-clock me-2"></i>
                                    ساعات العمل - {{ $government->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @php
                                    $hours = $government->getWorkingHours();
                                @endphp

                                @if (isset($hours['is_24h']) && $hours['is_24h'] === true)
                                    <div class="text-center py-4">
                                        <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                        <h5 class="text-success">مفتوح 24 ساعة</h5>
                                        <p class="text-muted">الجهة مفتوحة طوال أيام الأسبوع على مدار الساعة</p>
                                    </div>
                                @else
                                    <div class="work-hours-table">
                                        @php
                                            $daysMap = [
                                                'saturday' => 'السبت',
                                                'sunday' => 'الأحد',
                                                'monday' => 'الاثنين',
                                                'tuesday' => 'الثلاثاء',
                                                'wednesday' => 'الأربعاء',
                                                'thursday' => 'الخميس',
                                                'friday' => 'الجمعة',
                                            ];
                                        @endphp

                                        @foreach ($daysMap as $key => $name)
                                            @php
                                                $dayHours = $hours[$key] ?? null;
                                                $isClosed = empty($dayHours['open']) || empty($dayHours['close']);
                                            @endphp
                                            <div class="work-hour-row {{ $isClosed ? 'closed-row' : '' }}">
                                                <div class="day-name">{{ $name }}</div>
                                                <div class="day-hours">
                                                    @if ($isClosed)
                                                        <span class="closed-text">مغلق</span>
                                                    @else
                                                        <span class="hour-time">{{ $dayHours['open'] }}</span>
                                                        <i class="fas fa-arrow-left mx-2 text-muted"></i>
                                                        <span class="hour-time">{{ $dayHours['close'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function showFullWorkHours() {
                        const modal = new bootstrap.Modal(document.getElementById('workHoursModal'));
                        modal.show();
                    }
                </script>
                @if ($government->description)
                    @php
                        $fullDescription = $government->description;
                        $isLong = Str::length(strip_tags($fullDescription)) > 120;

                        // قص النص مع تجاهل HTML
                        $shortDescription = Str::limit(strip_tags($fullDescription), 120, '...');
                    @endphp
                    <div class="government-description mt-4 p-4"
                        style="background: linear-gradient(135deg, #f0f4ff, #ffffff); border-right: 4px solid #2f3e9e; border-radius: 16px;">
                        <i class="fas fa-quote-right text-primary me-2 opacity-50"></i>

                        <div class="expandable-text">
                            <input type="checkbox" id="expandCheckbox" class="expand-checkbox" style="display: none;">
                            <div class="expandable-content">
                                <div class="short-text">
                                    {!! nl2br(e($shortDescription)) !!}
                                </div>
                                <div class="full-text" style="display: none;">
                                    @if (Str::contains($fullDescription, '<'))
                                        {!! $fullDescription !!}
                                    @else
                                        {!! nl2br(e($fullDescription)) !!}
                                    @endif
                                </div>
                            </div>
                            @if ($isLong)
                                <label for="expandCheckbox" class="expand-label">
                                    <span class="expand-more">عرض المزيد <i class="fas fa-chevron-down"></i></span>
                                    <span class="expand-less">عرض أقل <i class="fas fa-chevron-up"></i></span>
                                </label>
                            @endif
                        </div>
                    </div>

                    <style>
                        .expand-checkbox:checked~.expandable-content .short-text {
                            display: none;
                        }

                        .expand-checkbox:checked~.expandable-content .full-text {
                            display: block !important;
                            margin-top: 12px;
                        }

                        .expand-checkbox:checked~.expand-label .expand-more {
                            display: none;
                        }

                        .expand-checkbox:checked~.expand-label .expand-less {
                            display: inline-flex;
                        }

                        .expand-label .expand-less {
                            display: none;
                        }

                        .expand-label {
                            cursor: pointer;
                            color: #2f3e9e;
                            font-size: 12px;
                            font-weight: 500;
                            margin-top: 8px;
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                        }

                        .expand-label:hover {
                            color: #5a6fc9;
                            text-decoration: underline;
                        }

                        .expand-label i {
                            font-size: 11px;
                        }

                        .short-text,
                        .full-text {
                            line-height: 1.8;
                            color: #4a5568;
                        }

                        .full-text ul,
                        .full-text ol {
                            padding-right: 20px;
                            margin: 10px 0;
                        }

                        .full-text li {
                            margin-bottom: 5px;
                        }

                        .full-text p {
                            margin-bottom: 12px;
                        }

                        .full-text h1,
                        .full-text h2,
                        .full-text h3,
                        .full-text h4,
                        .full-text h5,
                        .full-text h6 {
                            margin: 15px 0 10px 0;
                            color: #1a2c3e;
                        }
                    </style>
                @endif

                @if (count($images))
                    <div class="gallery-section mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0"><i class="fas fa-images text-primary me-2"></i>معرض الصور</h6>
                            <span class="badge" style="background: #e8eaf6; color: #2f3e9e;">{{ count($images) }}
                                صورة</span>
                        </div>
                        <div class="images-strip-container">
                            <div class="d-flex gap-3 overflow-auto py-2 images-strip">
                                @foreach ($images as $index => $img)
                                    <div class="gallery-item">
                                        <img src="{{ asset('storage/' . $img) }}" class="rounded-3 shadow-sm gov-thumb"
                                            data-full-img="{{ asset('storage/' . $img) }}" data-bs-toggle="modal"
                                            data-bs-target="#imagePreviewModal" alt="صورة {{ $government->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- الأكورديون -->
        <div class="accordion-custom" id="govAccordion">

            <!-- الموقع الجغرافي والخريطة -->
            <div class="accordion-item-custom mb-3">
                <div class="accordion-header-custom">
                    <button class="accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#locationCard">
                        <div class="accordion-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <span class="accordion-title">الموقع الجغرافي والخريطة</span>
                        <i class="fas fa-chevron-down accordion-arrow"></i>
                    </button>
                </div>
                <div id="locationCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                    <div class="accordion-body-custom">
                        <div class="map-box rounded-4 mb-4 overflow-hidden shadow-sm">
                            <div id="map" style="height: 350px; width: 100%;"></div>
                        </div>
                        @auth
                            <div class="directions-section">
                                <div class="d-flex gap-2 mb-3" role="group">
                                    <button type="button" class="direction-btn direction-active" id="btn-driving"
                                        data-profile="driving-car">
                                        <i class="fas fa-car me-2"></i> <span>سيارة</span>
                                    </button>
                                    <button type="button" class="direction-btn" id="btn-walking"
                                        data-profile="foot-walking">
                                        <i class="fas fa-walking me-2"></i> <span>مشي</span>
                                    </button>
                                </div>
                                <button id="useMyLocationBtn" class="btn btn-primary w-100 mb-3">
                                    <i class="fas fa-location-dot me-2"></i> استخدام موقعي الحالي للحصول على الاتجاهات
                                </button>
                                <div id="routeInfo" class="route-info d-none">
                                    <div class="route-card">
                                        <i class="fas fa-route route-icon"></i>
                                        <div class="route-details">
                                            <span class="route-label">المسافة:</span>
                                            <span id="distanceText" class="route-value fw-bold"></span>
                                            <span class="route-label ms-3">الزمن:</span>
                                            <span id="timeText" class="route-value fw-bold"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert-custom">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>لتتمكن من استخدام المسار ومشاهدة المسافة والوقت،</span>
                                <a href="{{ route('login') }}" class="alert-link">سجل الدخول</a>
                                <span>أو</span>
                                <a href="{{ route('register') }}" class="alert-link">أنشئ حساباً جديداً</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
            {{-- العروض الخاصة --}}
            @if ($government->offers && $government->offers->count() > 0)
                <div class="accordion-item-custom mb-3">
                    <div class="accordion-header-custom">
                        <button class="accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#offersCard">
                            <div class="accordion-icon"><i class="fas fa-gift"></i></div>
                            <span class="accordion-title">عروض ومميزات خاصة</span>
                            <span class="badge-count">{{ $government->offers->count() }}</span>
                            <i class="fas fa-chevron-down accordion-arrow"></i>
                        </button>
                    </div>
                    <div id="offersCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                        <div class="accordion-body-custom">
                            <div class="offers-grid">
                                @foreach ($government->offers as $offer)
                                    <div
                                        class="offer-card {{ $offer->isCurrentlyActive() ? 'active-offer' : 'expired-offer' }}">
                                        <div class="offer-icon">
                                            <i class="{{ $offer->icon ?? 'fas fa-tag' }}"></i>
                                        </div>
                                        <div class="offer-content">
                                            <div class="offer-title">
                                                {{ $offer->title }}
                                                @if ($offer->is_permanent)
                                                    <span class="badge bg-success ms-2">مستمر</span>
                                                @elseif(!$offer->isCurrentlyActive())
                                                    <span class="badge bg-secondary ms-2">منتهي</span>
                                                @endif
                                            </div>

                                            @if ($offer->description)
                                                <div class="offer-description">{{ $offer->description }}</div>
                                            @endif

                                            @if ($offer->target_audience)
                                                <div class="offer-audience mt-2">
                                                    <i class="fas fa-users"></i>
                                                    <span>الفئة المستهدفة: {{ $offer->target_audience }}</span>
                                                </div>
                                            @endif

                                            @if ($offer->terms)
                                                <div class="offer-terms mt-2">
                                                    <i class="fas fa-file-contract"></i>
                                                    <span>{{ $offer->terms }}</span>
                                                </div>
                                            @endif

                                            @if ($offer->start_date && $offer->end_date && !$offer->is_permanent)
                                                <div class="offer-date mt-2">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span>من {{ $offer->start_date->format('Y/m/d') }} إلى
                                                        {{ $offer->end_date->format('Y/m/d') }}</span>
                                                </div>
                                            @endif

                                            @if ($offer->contact_number)
                                                <div class="offer-contact mt-2">
                                                    <a href="tel:{{ $offer->contact_number }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-phone-alt"></i> استفسار
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- الخدمات المتوفرة - نسخة محسنة مع تجميع ديناميكي -->
            <div class="accordion-item-custom mb-3">
                <div class="accordion-header-custom">
                    <button class="accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#servicesCard">
                        <div class="accordion-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="accordion-title">الخدمات المتوفرة</span>
                        <span class="badge-count">{{ $government->services->count() }}</span>
                        <i class="fas fa-chevron-down accordion-arrow"></i>
                    </button>
                </div>
                <div id="servicesCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                    <div class="accordion-body-custom">
                        @if ($government->services->count() > 0)
                            <div class="services-groups-container" id="servicesGroupsContainer">

                                {{-- 1. عرض المجموعات (الخدمات التي تحتوي على -) --}}
                                @foreach ($groupedServices as $groupName => $subServices)
                                    @php $groupId = 'group-' . Str::slug($groupName); @endphp
                                    <div class="service-group-card">
                                        <button class="service-group-header collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}"
                                            aria-expanded="false">
                                            <div class="service-group-icon">
                                                <i class="fas fa-folder"></i>
                                            </div>
                                            <div class="service-group-title">
                                                <span class="group-name">{{ $groupName }}</span>
                                                <span class="group-count">({{ count($subServices) }})</span>
                                            </div>
                                            <i class="fas fa-chevron-down group-arrow"></i>
                                        </button>

                                        <div class="service-group-collapse collapse" id="{{ $groupId }}">
                                            <div class="services-grid-horizontal">
                                                @foreach ($subServices as $subService)
                                                    <div class="service-grid-item"
                                                        data-service-name='{{ json_encode($subService->display_name) }}'
                                                        data-service-desc='{{ json_encode($subService->description ?? '') }}'
                                                        data-service-contact='{{ json_encode($subService->contact_number) }}'
                                                        data-service-hours='{{ json_encode($subService->work_hours) }}'
                                                        data-service-price='{{ json_encode($subService->price) }}'
                                                        data-service-id='{{ json_encode($subService->service_id) }}'
                                                        data-service-icon='{{ json_encode($subService->icon_image) }}'
                                                        {{-- الحقول الجديدة --}}
                                                        data-processing-time='{{ json_encode($subService->processing_time ?? '') }}'
                                                        data-office-location='{{ json_encode($subService->office_location ?? '') }}'
                                                        data-required-documents='{{ json_encode($subService->required_documents ?? '') }}'
                                                        data-steps='{{ json_encode($subService->steps ?? '') }}'
                                                        data-conditions='{{ json_encode($subService->conditions ?? '') }}'
                                                        data-notes='{{ json_encode($subService->notes ?? '') }}'
                                                        data-requires-appointment='{{ json_encode($subService->requires_appointment ?? false) }}'
                                                        data-appointment-phone='{{ json_encode($subService->appointment_phone ?? '') }}'
                                                        data-doctor-specialist='{{ json_encode($subService->doctor_specialist ?? '') }}'
                                                        data-hospital-stay-duration='{{ json_encode($subService->hospital_stay_duration ?? '') }}'
                                                        data-emergency-notes='{{ json_encode($subService->emergency_notes ?? '') }}'
                                                        onclick="showServiceDetailsModalFromData(this)">
                                                        <div class="grid-item-icon">
                                                            @if ($subService->icon_image)
                                                                <img src="{{ asset('storage/' . $subService->icon_image) }}"
                                                                    alt="{{ $subService->display_name }}"
                                                                    style="width: 32px; height: 32px; object-fit: contain;">
                                                            @else
                                                                <i class="fas fa-ambulance"></i>
                                                            @endif
                                                        </div>
                                                        <div class="grid-item-name">
                                                            {{ Str::limit($subService->display_name, 20) }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                                {{-- 2. عرض الخدمات الأخرى (التي لا تحتوي على -) --}}
                                @if (count($standaloneServices) > 0)
                                    @php $otherId = 'group-other-services'; @endphp
                                    <div class="service-group-card">
                                        <button class="service-group-header collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $otherId }}"
                                            aria-expanded="false">
                                            <div class="service-group-icon">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </div>
                                            <div class="service-group-title">
                                                <span class="group-name">خدمات أخرى</span>
                                                <span class="group-count">({{ count($standaloneServices) }})</span>
                                            </div>
                                            <i class="fas fa-chevron-down group-arrow"></i>
                                        </button>

                                        <div class="service-group-collapse collapse" id="{{ $otherId }}">
                                            <div class="services-grid-horizontal">
                                                @foreach ($standaloneServices as $service)
                                                    @foreach ($standaloneServices as $service)
                                                        <div class="service-grid-item"
                                                            data-service-name='{{ json_encode($service->name) }}'
                                                            data-service-desc='{{ json_encode($service->pivot->description ?? ($service->description ?? '')) }}'
                                                            data-service-contact='{{ json_encode($service->pivot->contact_number) }}'
                                                            data-service-hours='{{ json_encode($service->pivot->work_hours) }}'
                                                            data-service-price='{{ json_encode($service->pivot->price) }}'
                                                            data-service-id='{{ json_encode($service->id) }}'
                                                            data-service-icon='{{ json_encode($service->icon_image) }}'
                                                            {{-- الحقول الجديدة --}}
                                                            data-processing-time='{{ json_encode($service->pivot->processing_time) }}'
                                                            data-office-location='{{ json_encode($service->pivot->office_location) }}'
                                                            data-required-documents='{{ json_encode($service->pivot->required_documents) }}'
                                                            data-steps='{{ json_encode($service->pivot->steps) }}'
                                                            data-conditions='{{ json_encode($service->pivot->conditions) }}'
                                                            data-notes='{{ json_encode($service->pivot->notes) }}'
                                                            data-requires-appointment='{{ json_encode($service->pivot->requires_appointment) }}'
                                                            data-appointment-phone='{{ json_encode($service->pivot->appointment_phone) }}'
                                                            data-doctor-specialist='{{ json_encode($service->pivot->doctor_specialist) }}'
                                                            data-hospital-stay-duration='{{ json_encode($service->pivot->hospital_stay_duration) }}'
                                                            data-emergency-notes='{{ json_encode($service->pivot->emergency_notes) }}'
                                                            onclick="showServiceDetailsModalFromData(this)">
                                                            <div class="grid-item-icon">
                                                                @if ($service->icon_image)
                                                                    <img src="{{ asset('storage/' . $service->icon_image) }}"
                                                                        alt="{{ $service->name }}"
                                                                        style="width: 32px; height: 32px; object-fit: contain;">
                                                                @else
                                                                    <i class="fas fa-ambulance"></i>
                                                                @endif
                                                            </div>
                                                            <div class="grid-item-name">
                                                                {{ Str::limit($service->name, 20) }}
                                                            </div>
                                                            @if ($service->pivot->description)
                                                                <div class="grid-item-desc">
                                                                    {{ Str::limit($service->pivot->description, 25) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>لا توجد خدمات مسجلة حالياً</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- التقييمات والمراجعات -->
            <div class="accordion-item-custom mb-3">
                <div class="accordion-header-custom">
                    <button class="accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#reviewsCard">
                        <div class="accordion-icon"><i class="fas fa-star"></i></div>
                        <span class="accordion-title">التقييمات والمراجعات</span>
                        <span class="badge-count" id="reviewsCount">{{ $government->reviews_count }}</span>
                        <i class="fas fa-chevron-down accordion-arrow"></i>
                    </button>
                </div>
                <div id="reviewsCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                    <div class="accordion-body-custom">
                        <div class="reviews-summary-custom">
                            <div class="summary-rating">
                                <span class="rating-large" id="averageRating">0</span>
                                <div class="rating-stars-large"><i class="fas fa-star"></i></div>
                            </div>
                            <div class="summary-text"><span id="totalReviews" class="text-muted">0 تقييم</span></div>
                        </div>

                        @auth
                            <div class="review-form-custom" id="reviewForm">
                                <h6 class="fw-bold mb-3"><i class="fas fa-edit text-primary me-2"></i>أضف تقييمك</h6>
                                <div class="rating-input mb-3">
                                    <label class="form-label small text-muted">تقييمك</label>
                                    <div class="stars-input">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star star-rating" data-value="{{ $i }}"
                                                style="cursor: pointer;"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" id="ratingValue" value="0">
                                </div>
                                <div class="mb-3">
                                    <textarea id="reviewComment" class="form-control" rows="3" placeholder="شاركنا رأيك في هذه الجهة..."></textarea>
                                </div>
                                <button id="submitReview" class="btn btn-primary"><i
                                        class="fas fa-paper-plane me-2"></i>إرسال التقييم</button>
                                <div id="reviewMessage" class="mt-3"></div>
                            </div>
                        @else
                            <div class="alert-custom alert-info-custom">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>لتتمكن من إضافة تقييمك الخاص،</span>
                                <a href="{{ route('login') }}" class="alert-link">سجل الدخول</a>
                                <span>أو</span>
                                <a href="{{ route('register') }}" class="alert-link">أنشئ حساباً جديداً</a>
                            </div>
                        @endauth

                        <div id="reviewsList">
                            <div class="loading-reviews" id="loadingReviews">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p>جاري تحميل التقييمات...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الاتصال -->
            <div class="accordion-item-custom">
                <div class="accordion-header-custom">
                    <button class="accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#contactCard">
                        <div class="accordion-icon"><i class="fas fa-address-card"></i></div>
                        <span class="accordion-title">معلومات الاتصال</span>
                        <i class="fas fa-chevron-down accordion-arrow"></i>
                    </button>
                </div>
                <div id="contactCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                    <div class="accordion-body-custom">
                        @if ($government->contact_number)
                            <div class="contact-info-custom">
                                {{-- رقم الهاتف --}}
                                @if ($government->contact_number)
                                    <div class="contact-item-custom">
                                        <div class="contact-icon-custom"><i class="fas fa-phone-alt"></i></div>
                                        <div class="contact-details">
                                            <div class="contact-label">رقم الهاتف</div>
                                            <div class="contact-value">
                                                <a href="tel:{{ $government->contact_number }}"
                                                    class="text-decoration-none">{{ $government->contact_number }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- رقم الواتساب --}}
                                @if ($government->whatsapp_number)
                                    <div class="contact-item-custom">
                                        <div class="contact-icon-custom" style="background: #e8f5e9;"><i
                                                class="fab fa-whatsapp" style="color: #25D366;"></i></div>
                                        <div class="contact-details">
                                            <div class="contact-label">واتساب</div>
                                            <div class="contact-value">
                                                <a href="https://wa.me/{{ $government->whatsapp_number }}"
                                                    class="text-decoration-none"
                                                    target="_blank">{{ $government->whatsapp_number }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- البريد الإلكتروني --}}
                                @if ($government->email)
                                    <div class="contact-item-custom">
                                        <div class="contact-icon-custom"><i class="fas fa-envelope"></i></div>
                                        <div class="contact-details">
                                            <div class="contact-label">البريد الإلكتروني</div>
                                            <div class="contact-value">
                                                <a href="mailto:{{ $government->email }}"
                                                    class="text-decoration-none">{{ $government->email }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- وصف الموقع التفصيلي --}}
                                @if ($government->location_description)
                                    <div class="contact-item-custom">
                                        <div class="contact-icon-custom"><i class="fas fa-info-circle"></i></div>
                                        <div class="contact-details">
                                            <div class="contact-label">وصف الموقع</div>
                                            <div class="contact-value">{{ $government->location_description }}</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- روابط التواصل الاجتماعي --}}
                                @if ($government->facebook_url || $government->telegram_url)
                                    <div class="contact-item-custom">
                                        <div class="contact-icon-custom"><i class="fas fa-share-alt"></i></div>
                                        <div class="contact-details">
                                            <div class="contact-label">تابعنا على</div>
                                            <div class="contact-value">
                                                <div class="d-flex gap-2 mt-1">
                                                    @if ($government->facebook_url)
                                                        <a href="{{ $government->facebook_url }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fab fa-facebook-f"></i> فيسبوك
                                                        </a>
                                                    @endif
                                                    @if ($government->telegram_url)
                                                        <a href="{{ $government->telegram_url }}" target="_blank"
                                                            class="btn btn-sm btn-outline-info">
                                                            <i class="fab fa-telegram-plane"></i> تليجرام
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="empty-state"><i class="fas fa-phone-slash"></i>
                                <p>لا توجد معلومات اتصال مسجلة</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal عرض الصور -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-custom">
                    <img id="previewImage" src="" class="img-fluid rounded-4"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal عرض تفاصيل الخدمة - تصميم محسن -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content service-modal-content">
                <div class="modal-header service-modal-header">
                    <h5 class="modal-title fw-bold">تفاصيل الخدمة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body service-modal-body">
                    <div class="service-modal-icon" id="serviceModalIcon">
                        <i class="fas fa-ambulance fa-3x"></i>
                    </div>
                    <h4 class="fw-bold text-center mb-4 service-modal-name" id="serviceModalName"></h4>
                    <div id="serviceModalDetails" class="service-modal-details"></div>
                </div>
                <div class="modal-footer service-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <a href="#" id="serviceModalLink" class="btn btn-primary"><i
                            class="fas fa-info-circle me-2"></i>عرض صفحة الخدمة</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        /* خلفية بسيطة متناسقة بدون كرات متحركة */
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
            min-height: 100vh;
        }

        :root {
            --primary: #1b2463;
            --primary-light: #414970;
            --primary-dark: #161b41;
            --secondary: #ffc107;
            --bg-light: #fef9f0;
            --bg-gray: #f8f9fa;
            --text-dark: #1a2c3e;
            --text-muted: #6c757d;
        }

        .main-government-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .main-government-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.2) !important;
        }

        .government-name {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-dark);
            position: relative;
            padding-right: 20px;
        }

        .government-name::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, #171e3a 100%);
            border-radius: 4px;
        }

        .rating-badge {
            background: var(--bg-gray);
            padding: 6px 14px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .direction-btn {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            background: #ffffff;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            color: #6c757d;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .direction-btn:hover {
            border-color: var(--primary);
            background: #f0f4ff;
            color: var(--primary);
        }

        .direction-active {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-color: var(--primary);
            color: white;
        }

        .gallery-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
            margin-top: 0.5rem;
        }

        .images-strip {
            scrollbar-width: thin;
            scrollbar-color: var(--primary) #e0e0e0;
        }

        .images-strip::-webkit-scrollbar {
            height: 5px;
        }

        .images-strip::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-radius: 10px;
        }

        .images-strip::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .gov-thumb {
            width: 100px;
            height: 75px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .gov-thumb:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(47, 62, 158, 0.2);
        }

        .accordion-custom {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .accordion-item-custom {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .accordion-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            background: none;
            border: none;
            text-align: right;
            font-weight: 600;
            font-size: 16px;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s;
        }

        .accordion-btn:hover {
            background: #f8f9fa;
        }

        .accordion-icon {
            width: 32px;
            height: 32px;
            background: #e8eaf6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .badge-count {
            background: #e8eaf6;
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
        }

        .accordion-arrow {
            color: #adb5bd;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .accordion-btn:not(.collapsed) .accordion-arrow {
            transform: rotate(180deg);
        }

        .accordion-body-custom {
            padding: 0 24px 24px 24px;
            border-top: 1px solid #e9ecef;
        }

        .services-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .service-card-custom {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e9ecef;
        }

        .service-card-custom:hover {
            background: #ffffff;
            border-color: var(--primary);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(47, 62, 158, 0.1);
        }

        .service-icon-custom {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #e8eaf6, #ffffff);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 22px;
        }

        .service-name-custom {
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .service-desc-custom {
            font-size: 13px;
            color: var(--text-muted);
        }

        .service-meta {
            display: flex;
            gap: 16px;
            margin-top: 8px;
            font-size: 11px;
            color: var(--text-muted);
        }

        .service-arrow-custom {
            color: var(--primary);
            opacity: 0.5;
            transition: all 0.2s;
        }

        .service-card-custom:hover .service-arrow-custom {
            opacity: 1;
            transform: translateX(3px);
        }

        .contact-info-custom {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-item-custom {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 16px;
        }

        .contact-icon-custom {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
        }

        .reviews-summary-custom {
            background: linear-gradient(135deg, #f8f9fa, #fff5e8);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
            border: 1px solid #e9ecef;
        }

        .rating-large {
            font-size: 48px;
            font-weight: 800;
            color: var(--primary);
        }

        .rating-stars-large i {
            font-size: 28px;
            color: #ffc107;
        }

        .review-form-custom {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e9ecef;
        }

        .stars-input i {
            font-size: 24px;
            margin-left: 4px;
            transition: all 0.1s;
        }

        .stars-input i:hover {
            transform: scale(1.1);
        }

        .review-card-custom {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
        }

        .btn-favorited {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 40px;
        }

        .btn-outline-favorite {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 10px 20px;
            border-radius: 40px;
        }

        .btn-outline-favorite:hover {
            background: #dc3545;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
        }

        .alert-custom {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 16px 20px;
            text-align: center;
            color: var(--text-muted);
        }

        .alert-info-custom {
            background: #e8eaf6;
            color: var(--primary);
        }

        .alert-link {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .route-info {
            margin-top: 16px;
        }

        .route-card {
            background: #f0f4ff;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .route-icon {
            font-size: 24px;
            color: var(--primary);
        }

        .loading-reviews {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
        }

        .modal-content-custom {
            background: #ffffff;
            border-radius: 28px;
            border: none;
            overflow: hidden;
        }

        .modal-header-custom {
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff;
        }

        .service-modal-icon {
            background: #e8eaf6;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        /* تنسيقات مودال الخدمة المحسن */
        .service-modal-content {
            border: none;
            border-radius: 28px;
            overflow: hidden;
            background: #fff;
        }

        .service-modal-header {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            color: white;
            padding: 20px 24px;
            border: none;
        }

        .service-modal-header .modal-title {
            color: white;
            font-size: 1.2rem;
        }

        .service-modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .service-modal-header .btn-close:hover {
            opacity: 1;
        }

        .service-modal-body {
            padding: 30px 24px;
        }

        .service-modal-icon i {
            font-size: 40px;
            color: #2f3e9e;
        }

        .service-modal-name {
            color: #1a2c3e;
            font-size: 1.5rem;
        }

        .service-modal-details {
            margin-top: 20px;
        }

        .service-modal-details p {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .service-modal-details .info-box {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-right: 3px solid #2f3e9e;
        }

        .service-modal-details .info-box i {
            font-size: 20px;
            color: #2f3e9e;
            width: 32px;
        }

        .service-modal-details .info-box .info-content {
            flex: 1;
        }

        .service-modal-details .info-box .info-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .service-modal-details .info-box .info-value {
            font-weight: 600;
            color: #1a2c3e;
        }

        .service-modal-footer {
            padding: 16px 24px 24px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .service-modal-footer .btn-secondary {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: #6c757d;
            padding: 8px 20px;
            border-radius: 40px;
            transition: all 0.2s;
        }

        .service-modal-footer .btn-secondary:hover {
            background: #e9ecef;
        }

        .service-modal-footer .btn-primary {
            background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
            border: none;
            padding: 8px 24px;
            border-radius: 40px;
            transition: all 0.2s;
        }

        .service-modal-footer .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
        }

        /* ===== تصميم المجموعات والشبكة الأفقية ===== */
        .services-groups-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .service-group-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .service-group-header {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f8f9fa;
            border: none;
            text-align: right;
            cursor: pointer;
            transition: all 0.2s;
        }

        .service-group-header:hover {
            background: #e8eaf6;
        }

        .service-group-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #e8eaf6, #ffffff);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f3e9e;
        }

        .service-group-title {
            flex: 1;
            text-align: right;
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }

        .group-name {
            font-weight: 700;
            font-size: 15px;
            color: #1a2c3e;
        }

        .group-count {
            font-size: 12px;
            color: #6c757d;
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 30px;
        }

        .group-arrow {
            color: #adb5bd;
            font-size: 12px;
            transition: transform 0.2s;
        }

        .service-group-header:not(.collapsed) .group-arrow {
            transform: rotate(180deg);
        }

        .service-group-collapse {
            border-top: 1px solid #e9ecef;
        }

        /* الشبكة الأفقية */
        .services-grid-horizontal {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 16px;
        }

        .service-grid-item {
            width: calc(33.33% - 7px);
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e9ecef;
        }

        .service-grid-item:hover {
            background: #e8eaf6;
            border-color: #2f3e9e;
            transform: translateY(-3px);
        }

        .grid-item-icon {
            font-size: 22px;
            color: #2f3e9e;
            margin-bottom: 8px;
        }

        .grid-item-name {
            font-weight: 600;
            font-size: 13px;
            color: #1a2c3e;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .grid-item-desc {
            font-size: 10px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* للشاشات الصغيرة */
        @media (max-width: 500px) {
            .service-grid-item {
                width: calc(50% - 5px);
            }
        }

        @media (max-width: 350px) {
            .service-grid-item {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .government-name {
                font-size: 1.5rem;
            }

            .accordion-btn {
                padding: 14px 16px;
                font-size: 14px;
            }

            .service-card-custom {
                padding: 12px;
                gap: 12px;
            }

            .rating-large {
                font-size: 36px;
            }

            .direction-btn {
                padding: 10px 16px;
                font-size: 12px;
            }
        }

        /* تنسيق الوصف المختصر */
        .short-description {
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }

        .short-description:hover {
            background: rgba(47, 62, 158, 0.05);
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

        /* للشاشات الصغيرة */
        @media (max-width: 768px) {
            .description-modal .modal-body {
                padding: 16px;
                font-size: 14px;
            }
        }

        /* ضمان ظهور المودال */
        #fullDescriptionModal {
            display: none;
        }

        #fullDescriptionModal.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        body.modal-open {
            overflow: hidden;
        }

        /* ===== إصلاح مشاكل المرور والهواتف ===== */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        .service-grid-item,
        .service-group-header,
        .service-card-custom,
        .btn,
        button {
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease;
            user-select: none;
        }

        /* للهواتف والأجهزة اللوحية */
        @media (max-width: 768px) {

            .service-grid-item:hover,
            .service-group-header:hover,
            .service-card-custom:hover {
                transform: none !important;
                background: #f8f9fa !important;
            }

            .service-grid-item:active,
            .service-group-header:active,
            .service-card-custom:active {
                background: #e8eaf6 !important;
                transform: scale(0.98) !important;
                transition: transform 0.05s !important;
            }
        }

        /* منع مشاكل الماوس داخل المودال */
        .modal {
            pointer-events: auto;
        }

        .modal-content {
            pointer-events: auto;
        }

        /* ===== تبسيط كامل لجميع التأثيرات ===== */

        /* إزالة جميع التحويلات */
        .service-grid-item,
        .service-group-header,
        .service-card-custom {
            transition: background 0.08s linear !important;
            transform: none !important;
        }

        /* تأثير hover بسيط جداً */
        .service-grid-item:hover {
            background: #e8eaf6 !important;
        }

        .service-group-header:hover {
            background: #e8eaf6 !important;
        }

        .service-card-custom:hover {
            background: #ffffff !important;
            border-color: #2f3e9e !important;
        }

        /* إزالة تأثير السهم المتحرك */
        .service-arrow-custom {
            opacity: 0.5;
            transition: none !important;
        }

        .service-card-custom:hover .service-arrow-custom {
            opacity: 1;
            transform: none !important;
        }

        /* إزالة transform من جميع العناصر */


        /* استثناء فقط للعناصر التي تحتاج transform (مثل الأكورديون) */
        .accordion-btn:not(.collapsed) .accordion-arrow {
            transform: rotate(180deg) !important;
        }

        /* تنسيقات العروض الخاصة */
        .offers-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .offer-card {
            display: flex;
            gap: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #fff5e8, #ffffff);
            border-radius: 16px;
            border-right: 4px solid #ffc107;
            transition: all 0.2s;
        }

        .offer-card.active-offer {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            border-right-color: #4caf50;
        }

        .offer-card.expired-offer {
            opacity: 0.6;
            background: #f8f9fa;
            border-right-color: #9e9e9e;
        }

        .offer-icon {
            width: 48px;
            height: 48px;
            background: #fff3e0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ff9800;
            flex-shrink: 0;
        }

        .active-offer .offer-icon {
            background: #e8f5e9;
            color: #4caf50;
        }

        .offer-content {
            flex: 1;
        }

        .offer-title {
            font-weight: 700;
            font-size: 16px;
            color: #1a2c3e;
            margin-bottom: 8px;
        }

        .offer-description {
            font-size: 14px;
            color: #6c757d;
        }

        .offer-audience,
        .offer-terms,
        .offer-date {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .offer-audience i,
        .offer-terms i,
        .offer-date i {
            width: 18px;
            color: #2f3e9e;
        }

        .offer-contact {
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .offer-card {
                flex-direction: column;
                gap: 8px;
            }

            .offer-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        /* حالة الدوام */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-open {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-closed {
            background: #ffebee;
            color: #c62828;
        }

        /* تنسيقات ساعات العمل */
        .work-hours-text {
            cursor: pointer;
            transition: color 0.2s;
        }

        .work-hours-text:hover {
            color: var(--primary) !important;
            text-decoration: underline;
        }

        .work-hours-table {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .work-hour-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 12px;
            border-right: 3px solid #2f3e9e;
        }

        .work-hour-row.closed-row {
            background: #fff5f5;
            border-right-color: #dc3545;
        }

        .day-name {
            font-weight: 600;
            color: #1a2c3e;
        }

        .day-hours {
            font-size: 14px;
        }

        .hour-time {
            font-weight: 500;
            color: #2f3e9e;
            background: white;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .closed-text {
            color: #dc3545;
            font-weight: 500;
        }

        .status-unknown {
            background: #f0f0f0;
            color: #9e9e9e;
        }

        .modal.fade .modal-dialog {
            transition: none;
        }

        /* إصلاح مشكلة وميض المودال */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }

        /* منع التداخل مع العناصر الأخرى */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }

        /* تنسيق محتوى المودال */
        .description-modal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
            padding: 24px;
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

        .description-modal .modal-header .btn-close:hover {
            opacity: 1;
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

    <script>
        // تمرير بيانات الموقع
        window.govData = {
            lat: {{ $government->location_lat ?? 'null' }},
            lng: {{ $government->location_long ?? 'null' }},
            name: @json($government->name)
        };
        window.isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        // أزرار الاتجاهات
        const btnDriving = document.getElementById('btn-driving');
        const btnWalking = document.getElementById('btn-walking');
        if (btnDriving && btnWalking) {
            btnDriving.addEventListener('click', function() {
                btnDriving.classList.add('direction-active');
                btnWalking.classList.remove('direction-active');
            });
            btnWalking.addEventListener('click', function() {
                btnWalking.classList.add('direction-active');
                btnDriving.classList.remove('direction-active');
            });
        }

        // معالجة الخريطة
        var locationCard = document.getElementById('locationCard');
        if (locationCard) {
            locationCard.addEventListener('shown.bs.collapse', function() {
                if (window.mapInstance) window.mapInstance.invalidateSize();
            });
        }

        // معرض الصور
        document.querySelectorAll('.gov-thumb').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('previewImage').src = this.getAttribute('data-full-img') || this
                    .src;
            });
        });

        // ===== عرض تفاصيل الخدمة في المودال =====
        document.querySelectorAll('.service-card-custom').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                const serviceId = this.dataset.serviceId;
                const serviceName = this.dataset.serviceName;
                const serviceDescription = this.dataset.serviceDescription ||
                    'لا يوجد وصف مفصل لهذه الخدمة.';
                const serviceContact = this.dataset.serviceContact;
                const serviceHours = this.dataset.serviceHours;
                const servicePrice = this.dataset.servicePrice;

                let detailsHtml = `<p class="text-muted">${escapeHtml(serviceDescription)}</p>`;

                if (serviceContact) {
                    detailsHtml += `
                        <div class="info-box">
                            <i class="fas fa-phone-alt"></i>
                            <div class="info-content">
                                <div class="info-label">رقم الاتصال الخاص</div>
                                <div class="info-value"><a href="tel:${escapeHtml(serviceContact)}">${escapeHtml(serviceContact)}</a></div>
                            </div>
                        </div>`;
                }
                if (serviceHours) {
                    detailsHtml += `
                        <div class="info-box">
                            <i class="fas fa-clock"></i>
                            <div class="info-content">
                                <div class="info-label">ساعات العمل لهذه الخدمة</div>
                                <div class="info-value">${escapeHtml(serviceHours)}</div>
                            </div>
                        </div>`;
                }
                if (servicePrice) {
                    detailsHtml += `
                        <div class="info-box">
                            <i class="fas fa-tag"></i>
                            <div class="info-content">
                                <div class="info-label">الرسوم</div>
                                <div class="info-value"><span class="price-badge" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 30px; padding: 4px 14px; font-size: 12px;">${escapeHtml(servicePrice)}</span></div>
                            </div>
                        </div>`;
                }

                document.getElementById('serviceModalName').innerText = serviceName;
                document.getElementById('serviceModalDetails').innerHTML = detailsHtml;

                // تعيين رابط عرض صفحة الخدمة
                const serviceLink = document.getElementById('serviceModalLink');
                serviceLink.href = `/services/${serviceId}`;

                // عرض المودال
                const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
                modal.show();
            });
        });

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // تحميل التقييمات
        function loadReviews() {
            const reviewsList = document.getElementById('reviewsList');
            const loadingReviews = document.getElementById('loadingReviews');
            fetch(`/governments/{{ $government->id }}/reviews`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('averageRating').innerText = data.average_rating;
                        document.getElementById('totalReviews').innerText = data.total_reviews + ' تقييم';
                        document.getElementById('reviewsCount').innerText = data.total_reviews;
                        if (data.reviews.length === 0) {
                            reviewsList.innerHTML =
                                `<div class="empty-state"><i class="fas fa-comment-slash"></i><p>لا توجد تقييمات لهذه الجهة بعد. كن أول من يقيم!</p></div>`;
                        } else {
                            let html = '';
                            data.reviews.forEach(review => {
                                let stars = '';
                                for (let i = 1; i <= 5; i++) stars += i <= review.rating ?
                                    '<i class="fas fa-star text-warning"></i>' :
                                    '<i class="far fa-star text-warning"></i>';
                                html +=
                                    `<div class="review-card-custom"><div class="review-header"><div><span class="reviewer-name">${review.user_name}</span><div class="review-stars mt-1">${stars}</div></div><small class="review-date">${review.created_at}</small></div><p class="review-comment">${review.comment || '<span class="text-muted fst-italic">لا يوجد تعليق</span>'}</p></div>`;
                            });
                            reviewsList.innerHTML = html;
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                    reviewsList.innerHTML = '<div class="empty-state text-danger">حدث خطأ في تحميل التقييمات</div>';
                })
                .finally(() => {
                    if (loadingReviews) loadingReviews.remove();
                });
        }

        // إضافة تقييم جديد
        const submitBtn = document.getElementById('submitReview');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                const rating = document.getElementById('ratingValue').value;
                const comment = document.getElementById('reviewComment').value;
                const messageDiv = document.getElementById('reviewMessage');
                if (rating == 0) {
                    messageDiv.innerHTML = '<div class="alert alert-warning">الرجاء اختيار تقييم</div>';
                    return;
                }
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';
                fetch('{{ route('reviews.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            government_id: {{ $government->id }},
                            rating: rating,
                            comment: comment
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            document.getElementById('ratingValue').value = 0;
                            document.getElementById('reviewComment').value = '';
                            document.querySelectorAll('.star-rating').forEach(star => {
                                star.classList.remove('fas');
                                star.classList.add('far');
                            });
                            loadReviews();
                        } else {
                            messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                        }
                    })
                    .catch(error => {
                        messageDiv.innerHTML =
                            '<div class="alert alert-danger">حدث خطأ، يرجى المحاولة مرة أخرى</div>';
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>إرسال التقييم';
                        setTimeout(() => {
                            messageDiv.innerHTML = '';
                        }, 5000);
                    });
            });
        }

        // نظام النجوم
        document.querySelectorAll('.star-rating').forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                document.getElementById('ratingValue').value = value;
                document.querySelectorAll('.star-rating').forEach(s => {
                    if (s.dataset.value <= value) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
            star.addEventListener('mouseenter', function() {
                const value = this.dataset.value;
                document.querySelectorAll('.star-rating').forEach(s => {
                    if (s.dataset.value <= value) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
            star.addEventListener('mouseleave', function() {
                const currentRating = document.getElementById('ratingValue').value;
                document.querySelectorAll('.star-rating').forEach(s => {
                    if (s.dataset.value <= currentRating) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
        });

        const reviewsCard = document.getElementById('reviewsCard');
        if (reviewsCard) reviewsCard.addEventListener('shown.bs.collapse', function() {
            loadReviews();
        });

        // تحسين دالة showServiceDetailsModal للتأكد من عمل الرابط
        function showServiceDetailsModal(service) {
            console.log('Service data:', service); // للتشخيص

            let detailsHtml = `<p class="text-muted">${escapeHtml(service.description || 'لا يوجد وصف مفصل')}</p>`;

            if (service.contact && service.contact !== 'null' && service.contact !== '') {
                detailsHtml += `
        <div class="info-box">
            <i class="fas fa-phone-alt"></i>
            <div class="info-content">
                <div class="info-label">رقم الاتصال</div>
                <div class="info-value"><a href="tel:${escapeHtml(service.contact)}">${escapeHtml(service.contact)}</a></div>
            </div>
        </div>`;
            }

            if (service.hours && service.hours !== 'null' && service.hours !== '') {
                detailsHtml += `
        <div class="info-box">
            <i class="fas fa-clock"></i>
            <div class="info-content">
                <div class="info-label">ساعات العمل</div>
                <div class="info-value">${escapeHtml(service.hours)}</div>
            </div>
        </div>`;
            }

            if (service.price && service.price !== 'null' && service.price !== '') {
                detailsHtml += `
        <div class="info-box">
            <i class="fas fa-tag"></i>
            <div class="info-content">
                <div class="info-label">الرسوم</div>
                <div class="info-value"><span style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 30px; padding: 4px 14px; font-size: 12px;">${escapeHtml(service.price)}</span></div>
            </div>
        </div>`;
            }

            document.getElementById('serviceModalName').innerText = service.name;
            document.getElementById('serviceModalDetails').innerHTML = detailsHtml;

            const serviceLink = document.getElementById('serviceModalLink');

            // ✅ تحسين: التأكد من وجود serviceId صالح
            if (service.serviceId && service.serviceId !== 'null' && service.serviceId !== '' && service.serviceId !==
                undefined && service.serviceId !== 0) {
                serviceLink.href = `/services/${service.serviceId}`;
                serviceLink.style.display = 'inline-flex';
                serviceLink.style.pointerEvents = 'auto';
                serviceLink.style.opacity = '1';
            } else {
                // إذا لم يوجد serviceId، أخف الزر أو عطله
                serviceLink.style.display = 'none';
                console.warn('الخدمة التالية ليس لها serviceId:', service);
            }

            const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
            modal.show();
        }

        function showServiceDetailsModalFromData(element) {
            try {
                const service = {
                    name: JSON.parse(element.dataset.serviceName || '""'),
                    description: JSON.parse(element.dataset.serviceDesc || '""'),
                    contact: JSON.parse(element.dataset.serviceContact || '""'),
                    hours: JSON.parse(element.dataset.serviceHours || '""'),
                    price: JSON.parse(element.dataset.servicePrice || '""'),
                    serviceId: JSON.parse(element.dataset.serviceId || '""'),
                    icon_image: JSON.parse(element.dataset.serviceIcon || '""'),
                    // الحقول الجديدة - تأكد من إضافتها في data attributes
                    processing_time: JSON.parse(element.dataset.processingTime || '""'),
                    office_location: JSON.parse(element.dataset.officeLocation || '""'),
                    required_documents: JSON.parse(element.dataset.requiredDocuments || '""'),
                    steps: JSON.parse(element.dataset.steps || '""'),
                    conditions: JSON.parse(element.dataset.conditions || '""'),
                    notes: JSON.parse(element.dataset.notes || '""'),
                    requires_appointment: JSON.parse(element.dataset.requiresAppointment || 'false'),
                    appointment_phone: JSON.parse(element.dataset.appointmentPhone || '""'),
                    doctor_specialist: JSON.parse(element.dataset.doctorSpecialist || '""'),
                    hospital_stay_duration: JSON.parse(element.dataset.hospitalStayDuration || '""'),
                    emergency_notes: JSON.parse(element.dataset.emergencyNotes || '""')
                };

                console.log('Service from data:', service);

                let detailsHtml = '';

                // الوصف
                if (service.description && service.description !== 'null' && service.description !== '') {
                    detailsHtml += `<p class="text-muted">${escapeHtml(service.description)}</p>`;
                }

                // الأوراق المطلوبة
                if (service.required_documents && service.required_documents !== 'null' && service.required_documents !==
                    '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-file-alt"></i>
                    <div class="info-content">
                        <div class="info-label">📄 الأوراق المطلوبة</div>
                        <div class="info-value" style="white-space: pre-line;">${escapeHtml(service.required_documents)}</div>
                    </div>
                </div>`;
                }

                // الإجراءات خطوة بخطوة
                if (service.steps && service.steps !== 'null' && service.steps !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-list-ol"></i>
                    <div class="info-content">
                        <div class="info-label">📋 الإجراءات خطوة بخطوة</div>
                        <div class="info-value" style="white-space: pre-line;">${escapeHtml(service.steps)}</div>
                    </div>
                </div>`;
                }

                // مدة الإنجاز
                if (service.processing_time && service.processing_time !== 'null' && service.processing_time !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-hourglass-half"></i>
                    <div class="info-content">
                        <div class="info-label">⏱️ مدة الإنجاز</div>
                        <div class="info-value">${escapeHtml(service.processing_time)}</div>
                    </div>
                </div>`;
                }

                // موقع التقديم
                if (service.office_location && service.office_location !== 'null' && service.office_location !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-door-open"></i>
                    <div class="info-content">
                        <div class="info-label">📍 موقع التقديم</div>
                        <div class="info-value">${escapeHtml(service.office_location)}</div>
                    </div>
                </div>`;
                }

                // الشروط
                if (service.conditions && service.conditions !== 'null' && service.conditions !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-clipboard-list"></i>
                    <div class="info-content">
                        <div class="info-label">📜 الشروط</div>
                        <div class="info-value" style="white-space: pre-line;">${escapeHtml(service.conditions)}</div>
                    </div>
                </div>`;
                }

                // ملاحظات إضافية
                if (service.notes && service.notes !== 'null' && service.notes !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-pen-alt"></i>
                    <div class="info-content">
                        <div class="info-label">📝 ملاحظات إضافية</div>
                        <div class="info-value" style="white-space: pre-line;">${escapeHtml(service.notes)}</div>
                    </div>
                </div>`;
                }

                // رقم الاتصال الخاص
                if (service.contact && service.contact !== 'null' && service.contact !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-phone-alt"></i>
                    <div class="info-content">
                        <div class="info-label">📞 رقم الاتصال الخاص</div>
                        <div class="info-value"><a href="tel:${escapeHtml(service.contact)}">${escapeHtml(service.contact)}</a></div>
                    </div>
                </div>`;
                }

                // ساعات العمل
                if (service.hours && service.hours !== 'null' && service.hours !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-clock"></i>
                    <div class="info-content">
                        <div class="info-label">🕐 ساعات العمل لهذه الخدمة</div>
                        <div class="info-value">${escapeHtml(service.hours)}</div>
                    </div>
                </div>`;
                }

                // السعر
                if (service.price && service.price !== 'null' && service.price !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-tag"></i>
                    <div class="info-content">
                        <div class="info-label">💰 الرسوم</div>
                        <div class="info-value"><span style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 30px; padding: 4px 14px; font-size: 12px;">${escapeHtml(service.price)}</span></div>
                    </div>
                </div>`;
                }

                // يتطلب حجز مسبق
                if (service.requires_appointment === true || service.requires_appointment === 'true') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-calendar-check"></i>
                    <div class="info-content">
                        <div class="info-label">📅 يتطلب حجز مسبق</div>
                        <div class="info-value">نعم</div>
                    </div>
                </div>`;

                    if (service.appointment_phone && service.appointment_phone !== 'null' && service.appointment_phone !==
                        '') {
                        detailsHtml += `
                    <div class="info-box">
                        <i class="fas fa-phone-alt"></i>
                        <div class="info-content">
                            <div class="info-label">📞 رقم الحجز</div>
                            <div class="info-value"><a href="tel:${escapeHtml(service.appointment_phone)}">${escapeHtml(service.appointment_phone)}</a></div>
                        </div>
                    </div>`;
                    }
                }

                // حقول خاصة بالمستشفيات
                if (service.doctor_specialist && service.doctor_specialist !== 'null' && service.doctor_specialist !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-user-md"></i>
                    <div class="info-content">
                        <div class="info-label">👨‍⚕️ الطبيب/القسم المختص</div>
                        <div class="info-value">${escapeHtml(service.doctor_specialist)}</div>
                    </div>
                </div>`;
                }

                if (service.hospital_stay_duration && service.hospital_stay_duration !== 'null' && service
                    .hospital_stay_duration !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-procedures"></i>
                    <div class="info-content">
                        <div class="info-label">🏥 مدة التنويم المتوقعة</div>
                        <div class="info-value">${escapeHtml(service.hospital_stay_duration)}</div>
                    </div>
                </div>`;
                }

                if (service.emergency_notes && service.emergency_notes !== 'null' && service.emergency_notes !== '') {
                    detailsHtml += `
                <div class="info-box">
                    <i class="fas fa-ambulance"></i>
                    <div class="info-content">
                        <div class="info-label">🚨 ملاحظات للطوارئ</div>
                        <div class="info-value" style="white-space: pre-line;">${escapeHtml(service.emergency_notes)}</div>
                    </div>
                </div>`;
                }

                // إذا لم توجد أي بيانات
                if (detailsHtml === '') {
                    detailsHtml = '<p class="text-muted text-center">لا توجد تفاصيل إضافية لهذه الخدمة</p>';
                }

                // عرض اسم الخدمة
                document.getElementById('serviceModalName').innerText = service.name;

                // عرض أيقونة الخدمة
                const modalIcon = document.getElementById('serviceModalIcon');
                if (modalIcon) {
                    if (service.icon_image && service.icon_image !== 'null' && service.icon_image !== '') {
                        modalIcon.innerHTML =
                            `<img src="/storage/${service.icon_image}" style="width: 60px; height: 60px; object-fit: contain;">`;
                    } else {
                        modalIcon.innerHTML = `<i class="fas fa-ambulance fa-3x"></i>`;
                    }
                }

                document.getElementById('serviceModalDetails').innerHTML = detailsHtml;

                const serviceLink = document.getElementById('serviceModalLink');
                if (service.serviceId && service.serviceId !== 'null' && service.serviceId !== '') {
                    serviceLink.href = `/services/${service.serviceId}`;
                    serviceLink.style.display = 'inline-flex';
                } else {
                    serviceLink.style.display = 'none';
                }

                new bootstrap.Modal(document.getElementById('serviceModal')).show();
            } catch (e) {
                console.error('Error:', e);
                alert('حدث خطأ في عرض تفاصيل الخدمة');
            }
        }
        // إصلاح مشكلة وميض المودال
        document.addEventListener('DOMContentLoaded', function() {
            // التأكد من تهيئة المودالات بشكل صحيح
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.addEventListener('show.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                });
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.style.overflow = '';
                });
            });
        });
        // بديل لفتح المودال بدون استخدام data-bs-toggle
        function showFullDescription() {
            var myModal = new bootstrap.Modal(document.getElementById('fullDescriptionModal'));
            myModal.show();
        }
        // منع الوميض مع الحفاظ على الخريطة
    </script>
    <script>
        window.ORS_API_KEY = "{{ env('ORS_API_KEY') }}";
    </script>
   <script src="{{ app()->environment('local') ? asset('js/map.js') : secure_asset('js/map.js') }}"></script>
    <script src="{{ app()->environment('local') ? asset('js/favorite.js') : secure_asset('js/favorite.js') }}"></script>
@endpush
