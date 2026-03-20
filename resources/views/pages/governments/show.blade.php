@extends('layouts.app')

@section('title', $government->name)

@section('content')
    @php
        $images = $government->images ?? [];
    @endphp

    <div class="container py-4">

        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <div class="card-body">
                <h3 class="fw-bold mb-2">{{ $government->name }}</h3>
                <p class="text-muted small mb-3">{{ $government->description }}</p>

                <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
                    @if ($government->contact_number)
                        <div>📞 {{ $government->contact_number }}</div>
                    @endif
                    @if ($government->work_hours)
                        <div>🕒 {{ $government->work_hours }}</div>
                    @endif
                </div>

                @if (count($images))
                    <div class="mt-3">
                        <div class="d-flex gap-2 overflow-auto py-2 images-strip">
                            @foreach ($images as $index => $img)
                                <img src="{{ asset('storage/' . $img) }}" class="rounded-3 shadow-sm gov-thumb"
                                    style="width: 120px; height: 80px; object-fit: cover; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                    onclick="document.getElementById('previewImage').src=this.src"
                                    alt="صورة {{ $government->name }}">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="accordion mt-4" id="govAccordion">

                    <!-- الموقع الجغرافي والخريطة -->
                    <div class="accordion-item rounded-4 mb-3 border-0 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse"
                                data-bs-target="#locationCard">
                                📍 الموقع الجغرافي والخريطة
                            </button>
                        </h2>
                        <div id="locationCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <div class="map-box rounded-4 mb-3 shadow-sm"
                                    style="overflow: hidden; border: 1px solid #eee;">
                                    <div id="map" style="height: 300px; width: 100%; background: #f8f9fa;">
                                        <div class="p-5 text-center text-muted">جاري تحميل الخريطة...</div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <div class="btn-group mb-2" role="group">
                                        <button type="button" class="btn btn-outline-primary active"
                                            data-profile="driving-car">🚗 سيارة</button>
                                        <button type="button" class="btn btn-outline-primary"
                                            data-profile="foot-walking">🚶‍♂️ مشي</button>
                                    </div>

                                    <button id="useMyLocationBtn" class="btn btn-primary rounded-3">
                                        📍 استخدم موقعي الحالي للحصول على الاتجاهات
                                    </button>

                                    <div id="routeInfo" class="text-center text-muted small d-none p-2 bg-light rounded">
                                        المسافة التقريبية: <span id="distanceText" class="fw-bold text-dark"></span> |
                                        الزمن: <span id="timeText" class="fw-bold text-dark"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الخدمات المتوفرة -->
                    <div class="accordion-item rounded-4 mb-3 border-0 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse"
                                data-bs-target="#servicesCard">
                                🧾 الخدمات المتوفرة
                            </button>
                        </h2>
                        <div id="servicesCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <ul class="list-group list-group-flush">
                                    @forelse($government->services as $service)
                                        <li class="list-group-item border-0 ps-0">
                                            <i class="bi bi-check2-circle text-success me-2"></i> {{ $service->name }}
                                        </li>
                                    @empty
                                        <li class="list-group-item border-0 text-muted small">لا توجد خدمات مسجلة حالياً
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- التقييمات والمراجعات -->
                    <div class="accordion-item rounded-4 border-0 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed rounded-4" type="button" data-bs-toggle="collapse"
                                data-bs-target="#reviewsCard">
                                ⭐ التقييمات والمراجعات
                            </button>
                        </h2>
                        <div id="reviewsCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body text-center py-4 text-muted">
                                <i class="bi bi-star d-block mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">نظام التقييمات سيتم تفعيله قريباً.</p>
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
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <img id="previewImage" src="" class="w-100" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // تمرير بيانات الموقع لملف الجافاسكريبت
        window.govData = {
            lat: {{ $government->location_lat ?? 'null' }},
            lng: {{ $government->location_long ?? 'null' }},
            name: @json($government->name)
        };

        // معالجة مشكلة الخريطة عند فتح الـ Accordion
        var locationCard = document.getElementById('locationCard');
        locationCard.addEventListener('shown.bs.collapse', function() {
            if (window.mapInstance) {
                window.mapInstance.invalidateSize(); // لضمان ظهور الخريطة كاملة
            }
        });
    </script>
    <script src="{{ asset('js/map.js') }}"></script>
@endpush
