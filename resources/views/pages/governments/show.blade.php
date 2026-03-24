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
                            data-id="{{ $government->id }}" data-type="government"
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
                                <span class="badge bg-primary rounded-pill ms-2" id="reviewsCount">0</span>
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
                    reviewsList.innerHTML = '<div class="text-center text-danger py-4">حدث خطأ في تحميل التقييمات</div>';
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

                fetch('{{ route("reviews.store") }}', {
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
                    messageDiv.innerHTML = '<div class="alert alert-danger">حدث خطأ، يرجى المحاولة مرة أخرى</div>';
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

    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush
