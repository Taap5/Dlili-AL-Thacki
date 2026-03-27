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
                        @if ($government->reviews_count > 0)
                            <div class="mt-1">
                                <span class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($government->reviews_avg_rating))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-muted small ms-1">
                                    ({{ number_format($government->reviews_avg_rating, 1) }} -
                                    {{ $government->reviews_count }} تقييم)
                                </span>
                            </div>
                        @else
                            <div class="text-muted small mt-1">
                                <i class="far fa-star"></i> لا توجد تقييمات بعد
                            </div>
                        @endif
                    </div>
                    @auth
                        <button class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} favorite-btn"
                            data-id="{{ $government->id }}" data-type="government"
                            data-url="{{ route('favorite.government.toggle') }}">
                            <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart-broken' }} me-1"></i>
                            <span>{{ $isFavorited ? 'تمت الإضافة' : 'أضف إلى المفضلة' }}</span>
                        </button>
                    @endauth
                </div>

                <p class="text-muted mb-4">{{ $government->description }}</p>

                <!-- العنوان التفصيلي -->
                @if($government->address)
                    <div class="address-info mb-3">
                        <i class="fas fa-location-dot text-primary me-2"></i>
                        <span class="text-muted">{{ $government->address }}</span>
                    </div>
                @endif

                <!-- معرض الصور -->
                @if (count($images))
                    <div class="mb-4 images-strip-container">
                        <div class="d-flex gap-2 overflow-auto py-2 images-strip">
                            @foreach ($images as $index => $img)
                                <img src="{{ asset('storage/' . $img) }}" class="rounded-3 shadow-sm gov-thumb"
                                    data-full-img="{{ asset('storage/' . $img) }}" data-bs-toggle="modal"
                                    data-bs-target="#imagePreviewModal" alt="صورة {{ $government->name }}">
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
                                            <button type="button" class="btn btn-outline-primary active"
                                                data-profile="driving-car">
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

                    <!-- الخدمات المتوفرة (مع التفاصيل الخاصة) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#servicesCard">
                                <i class="fas fa-clipboard-list me-2"></i>
                                الخدمات المتوفرة
                                <span class="badge bg-primary rounded-pill ms-2">{{ $government->services->count() }}</span>
                            </button>
                        </h2>
                        <div id="servicesCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                @if($government->services->count() > 0)
                                    <div class="services-grid">
                                        @foreach($government->services as $service)
                                            <div class="service-card-clickable"
                                                 data-service-id="{{ $service->id }}"
                                                 data-service-name="{{ $service->name }}"
                                                 data-service-description="{{ $service->pivot->description ?? $service->description }}"
                                                 data-service-contact="{{ $service->pivot->contact_number }}"
                                                 data-service-hours="{{ $service->pivot->work_hours }}"
                                                 data-service-price="{{ $service->pivot->price }}">
                                                <div class="service-icon-small">
                                                    <i class="fas fa-concierge-bell"></i>
                                                </div>
                                                <div class="service-info">
                                                    <h6 class="service-name-clickable">{{ $service->name }}</h6>
                                                    <p class="service-desc-preview">{{ Str::limit($service->pivot->description ?? $service->description ?? 'لا يوجد وصف', 50) }}</p>
                                                    @if($service->pivot->contact_number)
                                                        <small class="text-muted d-block mt-1">
                                                            <i class="fas fa-phone-alt me-1"></i> {{ $service->pivot->contact_number }}
                                                        </small>
                                                    @endif
                                                    @if($service->pivot->work_hours)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-clock me-1"></i> {{ $service->pivot->work_hours }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="service-arrow">
                                                    <i class="fas fa-chevron-left"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                        <p class="mb-0">لا توجد خدمات مسجلة حالياً</p>
                                    </div>
                                @endif
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
                                <span class="badge bg-primary rounded-pill ms-2" id="reviewsCount">{{ $government->reviews_count }}</span>
                            </button>
                        </h2>
                        <div id="reviewsCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <!-- إحصائيات التقييمات -->
                                <div class="reviews-summary mb-4 p-3 bg-light rounded-4 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="display-6 fw-bold text-primary" id="averageRating">0</span>
                                        <span class="text-warning">
                                            <i class="fas fa-star"></i>
                                        </span>
                                        <span class="text-muted">|</span>
                                        <span id="totalReviews" class="text-muted">0 تقييم</span>
                                    </div>
                                </div>

                                <!-- نموذج إضافة تقييم (للمستخدم المسجل فقط) -->
                                @auth
                                    <div class="card border-0 shadow-sm rounded-4 mb-4" id="reviewForm">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3">أضف تقييمك</h5>
                                            <div class="mb-3">
                                                <label class="form-label">تقييمك</label>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="far fa-star fa-lg star-rating"
                                                            data-value="{{ $i }}"
                                                            style="cursor: pointer; color: #ffc107;"></i>
                                                    @endfor
                                                </div>
                                                <input type="hidden" id="ratingValue" value="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">تعليقك</label>
                                                <textarea id="reviewComment" class="form-control" rows="3" placeholder="شاركنا رأيك في هذه الجهة..."></textarea>
                                            </div>
                                            <button id="submitReview" class="btn btn-primary">
                                                <i class="fas fa-paper-plane me-2"></i>إرسال التقييم
                                            </button>
                                            <div id="reviewMessage" class="mt-2"></div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center mb-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>معلومة:</strong> لتتمكن من إضافة تقييمك الخاص،
                                        <a href="{{ route('login') }}" class="alert-link">سجل الدخول</a> أو
                                        <a href="{{ route('register') }}" class="alert-link">أنشئ حساباً جديداً</a>.
                                    </div>
                                @endauth

                                <!-- قائمة التقييمات -->
                                <div id="reviewsList">
                                    <div class="text-center py-4" id="loadingReviews">
                                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                                        <p class="mt-2">جاري تحميل التقييمات...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الاتصال (في الأسفل) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#contactCard">
                                <i class="fas fa-address-card me-2"></i>
                                معلومات الاتصال
                            </button>
                        </h2>
                        <div id="contactCard" class="accordion-collapse collapse" data-bs-parent="#govAccordion">
                            <div class="accordion-body">
                                <div class="contact-info-grid">
                                    @if ($government->contact_number)
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <div>
                                                <div class="contact-label">رقم الهاتف</div>
                                                <div class="contact-value">
                                                    <a href="tel:{{ $government->contact_number }}" class="text-decoration-none text-dark">
                                                        {{ $government->contact_number }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle me-1"></i> لا توجد معلومات اتصال مسجلة
                                        </div>
                                    @endif
                                </div>
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

    <!-- Modal عرض تفاصيل الخدمة -->
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">تفاصيل الخدمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="service-modal-icon text-center mb-3">
                        <i class="fas fa-concierge-bell fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-center mb-3" id="serviceModalName"></h4>
                    <div id="serviceModalDetails"></div>
                    <div class="text-center mt-4">
                        <a href="#" id="serviceModalLink" class="btn btn-outline-primary">
                            <i class="fas fa-info-circle me-2"></i>عرض صفحة الخدمة
                        </a>
                    </div>
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
                document.getElementById('previewImage').src = this.getAttribute('data-full-img') || this
                    .src;
            });
        });

        // عرض تفاصيل الخدمة في المودال (مع pivot)
        document.querySelectorAll('.service-card-clickable').forEach(card => {
            card.addEventListener('click', function() {
                const serviceId = this.dataset.serviceId;
                const serviceName = this.dataset.serviceName;
                const serviceDescription = this.dataset.serviceDescription || 'لا يوجد وصف مفصل لهذه الخدمة.';
                const serviceContact = this.dataset.serviceContact;
                const serviceHours = this.dataset.serviceHours;
                const servicePrice = this.dataset.servicePrice;

                let detailsHtml = `<p class="text-muted">${serviceDescription}</p>`;

                if (serviceContact) {
                    detailsHtml += `<div class="mt-3 p-2 bg-light rounded-3">
                                        <i class="fas fa-phone-alt text-primary me-2"></i>
                                        <strong>رقم الاتصال:</strong>
                                        <a href="tel:${serviceContact}" class="text-decoration-none">${serviceContact}</a>
                                    </div>`;
                }
                if (serviceHours) {
                    detailsHtml += `<div class="mt-2 p-2 bg-light rounded-3">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <strong>ساعات العمل:</strong> ${serviceHours}
                                    </div>`;
                }
                if (servicePrice) {
                    detailsHtml += `<div class="mt-2 p-2 bg-light rounded-3">
                                        <i class="fas fa-tag text-primary me-2"></i>
                                        <strong>الرسوم:</strong> ${servicePrice}
                                    </div>`;
                }

                document.getElementById('serviceModalName').innerText = serviceName;
                document.getElementById('serviceModalDetails').innerHTML = detailsHtml;
                document.getElementById('serviceModalLink').href = `/services/${serviceId}`;

                new bootstrap.Modal(document.getElementById('serviceModal')).show();
            });
        });
    </script>

    <!-- كود التقييمات -->
    <script>
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
                            reviewsList.innerHTML = `
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-comment-slash fa-2x mb-2 d-block"></i>
                                    <p class="mb-0">لا توجد تقييمات لهذه الجهة بعد. كن أول من يقيم!</p>
                                </div>
                            `;
                        } else {
                            let html = '';
                            data.reviews.forEach(review => {
                                let stars = '';
                                for (let i = 1; i <= 5; i++) {
                                    if (i <= review.rating) {
                                        stars += '<i class="fas fa-star text-warning"></i>';
                                    } else {
                                        stars += '<i class="far fa-star text-warning"></i>';
                                    }
                                }
                                html += `
                                    <div class="card border-0 shadow-sm rounded-4 mb-3" data-review-id="${review.id}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <strong class="fs-6">${review.user_name}</strong>
                                                    <div class="text-warning small">${stars}</div>
                                                </div>
                                                <small class="text-muted">${review.created_at}</small>
                                            </div>
                                            <p class="mb-0 text-muted">${review.comment || '<span class="text-muted fst-italic">لا يوجد تعليق</span>'}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            reviewsList.innerHTML = html;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    reviewsList.innerHTML =
                        '<div class="text-center text-danger py-4">حدث خطأ في تحميل التقييمات</div>';
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

        // تحميل التقييمات عند فتح الأكورديون
        const reviewsCard = document.getElementById('reviewsCard');
        if (reviewsCard) {
            reviewsCard.addEventListener('shown.bs.collapse', function() {
                loadReviews();
            });
        }
    </script>

    <style>
        /* تنسيقات بطاقات الخدمات */
        .services-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .service-card-clickable {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #ffffff !important;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e8eaf6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            opacity: 1 !important;
            visibility: visible !important;
        }

        .service-card-clickable:hover {
            background: #f8f9ff !important;
            border-color: #2f3e9e;
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(47,62,158,0.1);
        }

        .service-icon-small {
            width: 48px;
            height: 48px;
            background: #e8eaf6 !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f3e9e;
            font-size: 20px;
            flex-shrink: 0;
        }

        .service-info {
            flex: 1;
        }

        .service-name-clickable {
            font-weight: 700;
            margin-bottom: 4px;
            color: #1a2c3e;
        }

        .service-desc-preview {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }

        .service-arrow {
            color: #2f3e9e;
            font-size: 14px;
            opacity: 0.5;
            transition: all 0.2s;
        }

        .service-card-clickable:hover .service-arrow {
            opacity: 1;
            transform: translateX(3px);
        }

        /* التأكد من عدم وجود أي شفافية */
        .service-card-clickable * {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* تنسيقات معلومات الاتصال */
        .contact-info-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 16px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .contact-item:hover {
            background: #f0f2ff;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f3e9e;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .contact-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .contact-value {
            font-weight: 500;
            color: #1a2c3e;
        }

        .contact-value a {
            text-decoration: none;
            color: inherit;
        }

        .contact-value a:hover {
            color: #2f3e9e;
        }

        /* تنسيقات المودال */
        .modal-content {
            background: #ffffff !important;
            border: none !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
        }

        .modal-header {
            background: #ffffff !important;
            border-bottom: 1px solid #eef2f7 !important;
            border-radius: 24px 24px 0 0 !important;
        }

        .modal-body {
            background: #ffffff !important;
            padding: 1.5rem !important;
        }

        .modal-backdrop {
            background-color: rgba(0,0,0,0.5) !important;
        }

        .modal-backdrop.show {
            opacity: 1 !important;
            background-color: rgba(0,0,0,0.5) !important;
        }

        .service-modal-icon {
            background: #f0f2ff;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* إصلاح مشكلة scroll-hint */
        .images-strip-container {
            position: relative;
        }

        .scroll-hint {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 11px;
            pointer-events: none;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .images-strip-container:hover .scroll-hint {
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .scroll-hint {
                opacity: 0.5 !important;
                background: rgba(0,0,0,0.5);
                font-size: 10px;
                padding: 4px 10px;
            }
        }

        @media (max-width: 576px) {
            .service-card-clickable {
                padding: 12px;
            }
            .service-icon-small {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .service-name-clickable {
                font-size: 14px;
            }
            .contact-item {
                padding: 10px 12px;
            }
            .contact-icon {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            .contact-value {
                font-size: 14px;
            }
        }
    </style>

    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush
