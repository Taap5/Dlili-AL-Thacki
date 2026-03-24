@extends('layouts.app')

@section('title', $government->name)

@section('content')
    @php
        $images = $government->images ?? [];
        $isFavorited = Auth::check() ? Auth::user()->isGovernmentFavorite($government->id) : false;
    @endphp

    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden main-government-card">
            <div class="card-body p-4">
                <!-- رأس الجهة مع زر المفضلة -->
                <div class="d-flex justify-content-between align-items-start">
                    <div class="government-name-wrapper">
                        <h3 class="fw-bold mb-2 government-name">{{ $government->name }}</h3>
                    </div>

                    @auth
                        <button class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-btn"
                                data-id="{{ $government->id }}"
                                data-type="government"
                                data-url="{{ route('favorite.government.toggle') }}">
                            <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart-broken' }} me-1"></i>
                            <span>{{ $isFavorited ? 'تمت الإضافة' : 'أضف إلى المفضلة' }}</span>
                        </button>
                    @endauth
                </div>

                <p class="text-muted mb-4">{{ $government->description }}</p>

                <!-- معلومات الاتصال -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if ($government->contact_number)
                        <div class="info-pill">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ $government->contact_number }}</span>
                        </div>
                    @endif
                    @if ($government->work_hours)
                        <div class="info-pill">
                            <i class="fas fa-clock"></i>
                            <span>{{ $government->work_hours }}</span>
                        </div>
                    @endif
                </div>

                <!-- معرض الصور -->
                @if (count($images))
                    <div class="mb-4 images-strip-container">
                        <div class="d-flex gap-2 overflow-auto py-2 images-strip">
                            @foreach ($images as $index => $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="rounded-3 shadow-sm gov-thumb"
                                    data-full-img="{{ asset('storage/' . $img) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#imagePreviewModal"
                                    alt="صورة {{ $government->name }}">
                            @endforeach
                        </div>
                        <div class="scroll-hint d-md-none">
                            <i class="fas fa-chevron-left"></i> اسحب للمزيد <i class="fas fa-chevron-left"></i>
                        </div>
                    </div>
                @endif

                <!-- الأكورديون -->
                <div class="accordion" id="govAccordion">

                    <!-- الموقع الجغرافي والخريطة -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#locationCard">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الموقع الجغرافي والخريطة
                            </button>
                        </h2>
                        <div id="locationCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <div class="map-box rounded-4 mb-3">
                                    <div id="map" style="height: 300px; width: 100%;"></div>
                                </div>

                                <div class="d-grid gap-3">
                                    @auth
                                        <div class="btn-group d-flex justify-content-center gap-2" role="group">
                                            <button type="button" class="btn btn-outline-primary active" data-profile="driving-car">
                                                <i class="fas fa-car me-1"></i> سيارة
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" data-profile="foot-walking">
                                                <i class="fas fa-walking me-1"></i> مشي
                                            </button>
                                        </div>

                                        <button id="useMyLocationBtn" class="btn btn-primary">
                                            <i class="fas fa-location-dot me-2"></i>
                                            استخدم موقعي الحالي للحصول على الاتجاهات
                                        </button>

                                        <div id="routeInfo" class="text-center d-none">
                                            <i class="fas fa-route me-1"></i>
                                            المسافة: <span id="distanceText" class="fw-bold"></span> |
                                            الزمن: <span id="timeText" class="fw-bold"></span>
                                        </div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>معلومة:</strong> لتتمكن من استخدام المسار ومشاهدة المسافة والوقت،
                                            <a href="{{ route('login') }}" class="alert-link">سجل الدخول</a> أو
                                            <a href="{{ route('register') }}" class="alert-link">أنشئ حساباً جديداً</a>.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الخدمات المتوفرة -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#servicesCard">
                                <i class="fas fa-clipboard-list me-2"></i>
                                الخدمات المتوفرة
                            </button>
                        </h2>
                        <div id="servicesCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <div class="services-list">
                                    @forelse($government->services as $service)
                                        <div class="service-item-custom">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ $service->name }}</span>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                            <p class="mb-0">لا توجد خدمات مسجلة حالياً</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- التقييمات والمراجعات -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#reviewsCard">
                                <i class="fas fa-star me-2"></i>
                                التقييمات والمراجعات
                            </button>
                        </h2>
                        <div id="reviewsCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body text-center py-4">
                                <i class="fas fa-star fa-3x mb-3 text-muted"></i>
                                <p class="text-muted mb-0">نظام التقييمات سيتم تفعيله قريباً.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal عرض الصور -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img id="previewImage" src="" class="img-fluid rounded-4" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // تمرير بيانات الموقع
        window.govData = {
            lat: {{ $government->location_lat ?? 'null' }},
            lng: {{ $government->location_long ?? 'null' }},
            name: @json($government->name)
        };

        // تمرير حالة تسجيل الدخول
        window.isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        // معالجة مشكلة الخريطة عند فتح الـ Accordion
        var locationCard = document.getElementById('locationCard');
        if (locationCard) {
            locationCard.addEventListener('shown.bs.collapse', function() {
                if (window.mapInstance) {
                    window.mapInstance.invalidateSize();
                }
            });
        }

        // معرض الصور
        document.querySelectorAll('.gov-thumb').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('previewImage').src = this.getAttribute('data-full-img') || this.src;
            });
        });
    </script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush
