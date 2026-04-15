@extends('layouts.app')

@section('title', $service->name . ' - دليلي الذكي')

@section('content')
    @php
        // حساب إحصائيات الخدمة
        $minPrice = null;
        $minProcessingTime = null;
        $totalRating = 0;
        $ratingCount = 0;

        foreach ($service->governments as $gov) {
            if ($gov->pivot->price) {
                $price = (float) preg_replace('/[^0-9]/', '', $gov->pivot->price);
                if ($minPrice === null || $price < $minPrice) {
                    $minPrice = $price;
                }
            }
            if ($gov->pivot->processing_time && !$minProcessingTime) {
                $minProcessingTime = $gov->pivot->processing_time;
            }
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
        :root {
            --primary: #2f3e9e;
            --primary-light: #5a6fc9;
            --primary-dark: #1a2366;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-light: rgba(47, 62, 158, 0.1);
            --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
        }

        .service-detail-page {
            background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
            min-height: 100vh;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .service-detail-page::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(47, 62, 158, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatBg 25s infinite ease-in-out;
        }

        .service-detail-page::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(90, 111, 201, 0.04) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatBg 20s infinite ease-in-out reverse;
        }

        @keyframes floatBg {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 2;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            margin-bottom: 1.5rem;
        }

        .breadcrumb-custom a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }

        .breadcrumb-custom .separator {
            color: var(--text-muted);
            margin: 0 0.5rem;
        }

        .breadcrumb-custom .current {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Hero Card */
        .hero-card {
            background: white;
            border-radius: 28px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-light);
            box-shadow: var(--card-shadow);
        }

        .service-icon-large {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .service-icon-large i {
            font-size: 3rem;
            color: var(--primary);
        }

        .service-name {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--text-dark), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0f4ff;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            color: var(--primary);
        }

        .service-description {
            color: var(--text-muted);
            line-height: 1.6;
            margin-top: 1rem;
        }

        .read-more-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .favorite-action {
            width: 100%;
            padding: 0.75rem;
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.2s;
            cursor: pointer;
        }

        .favorite-action:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        .favorite-action.active {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-color: #ef4444;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 1rem;
            text-align: center;
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            background: #f1f5f9;
        }

        .stat-card i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Gallery */
        .gallery-wrapper {
            background: white;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-light);
        }

        .gallery-scroll {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .gallery-item {
            width: 100px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            flex-shrink: 0;
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            position: relative;
            padding-right: 1rem;
        }

        .section-title::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 4px;
        }

        .section-badge {
            background: #f0f4ff;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            color: var(--primary);
        }

        /* Government Cards */
        .governments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .government-card {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.3s;
        }

        .government-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow);
            border-color: var(--primary);
        }

        .card-img {
            height: 140px;
            width: 100%;
            object-fit: cover;
        }

        .card-img-placeholder {
            height: 140px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-img-placeholder i {
            font-size: 3rem;
            color: var(--primary-light);
        }

        .card-body {
            padding: 1.2rem;
        }

        .government-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .government-name a {
            color: var(--text-dark);
            text-decoration: none;
        }

        .government-name a:hover {
            color: var(--primary);
        }

        .quick-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .quick-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .quick-badge.price {
            background: #d1fae5;
            color: #065f46;
        }

        .quick-badge.time {
            background: #e0f2fe;
            color: #0369a1;
        }

        .toggle-details-btn {
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .service-details {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .detail-row {
            margin-bottom: 0.75rem;
        }

        .detail-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
        }

        .detail-value {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .gov-footer {
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-light);
        }

        .gov-contact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        /* Related Services */
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .related-card {
            background: white;
            border-radius: 20px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid var(--border-light);
            text-decoration: none;
        }

        .related-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: var(--card-shadow);
        }

        .related-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 0.5rem;
            background: #f0f4ff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .related-icon i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .related-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-custom {
                padding: 0 1rem;
            }

            .service-name {
                font-size: 1.4rem;
            }

            .governments-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="service-detail-page">
        <div class="container-custom">
            <!-- Breadcrumb -->
            <div class="breadcrumb-custom">
                <a href="{{ route('home') }}">الرئيسية</a>
                <span class="separator">/</span>
                <a href="{{ route('services.index') }}">الخدمات</a>
                <span class="separator">/</span>
                <span class="current">{{ $service->name }}</span>
            </div>

            <!-- Hero Card -->
            <div class="hero-card">
                <div class="row">
                    <div class="col-md-8">
                        <div class="service-icon-large">
                            @if ($service->icon_image)
                                <img src="{{ asset('storage/' . $service->icon_image) }}" loading="lazy"
                                    alt="{{ $service->name }}" style="width: 70px; height: 70px; object-fit: contain;">
                            @else
                                @php
                                    $categoryIcons = [
                                        1 => 'fa-hospital',
                                        2 => 'fa-shield-alt',
                                        3 => 'fa-envelope',
                                        4 => 'fa-id-card',
                                        5 => 'fa-passport',
                                    ];
                                    $catId = $service->government_category_id ?? 0;
                                    $icon = $categoryIcons[$catId] ?? 'fa-concierge-bell';
                                @endphp
                                <i class="fas {{ $icon }}"></i>
                            @endif
                        </div>
                        <h1 class="service-name">{{ $service->name }}</h1>
                        @if ($service->category)
                            <div class="category-badge">
                                <i class="fas fa-tag"></i>
                                <span>{{ $service->category->name }}</span>
                            </div>
                        @endif

                        @if ($service->description)
                            <div class="service-description">
                                <p>{{ Str::limit($service->description, 200) }}</p>
                                @if (Str::length($service->description) > 200)
                                    <button class="read-more-btn" onclick="openFullDescriptionModal()">
                                        <span>قراءة المزيد</span>
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                @endif
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <i class="fas fa-building"></i>
                                <div class="stat-number">{{ $service->governments->count() }}</div>
                                <div class="stat-label">جهة تقدم الخدمة</div>
                            </div>
                            @if ($minPrice)
                                <div class="stat-card">
                                    <i class="fas fa-tag"></i>
                                    <div class="stat-number">{{ number_format($minPrice) }}</div>
                                    <div class="stat-label">ريال (أقل سعر)</div>
                                </div>
                            @endif
                            @if ($avgServiceRating)
                                <div class="stat-card">
                                    <i class="fas fa-star"></i>
                                    <div class="stat-number">{{ $avgServiceRating }}</div>
                                    <div class="stat-label">متوسط التقييم</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0">
                        @auth
                            <button class="favorite-action {{ $isFavorited ? 'active' : '' }}" id="favoriteBtn">
                                <i class="fas fa-heart"></i>
                                <span>{{ $isFavorited ? 'تمت الإضافة' : 'أضف للمفضلة' }}</span>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            @if (count($images) > 0)
                <div class="gallery-wrapper">
                    <div class="gallery-scroll">
                        @foreach ($images as $index => $img)
                            <div class="gallery-item" onclick="openGallery({{ $index }})">
                                <img src="{{ asset('storage/' . $img) }}" loading="lazy" alt="صورة الخدمة">
                                <div class="gallery-overlay"
                                    style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s;">
                                    <i class="fas fa-search-plus" style="color: white;"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Governments Section -->
            <div class="section-header">
                <h2 class="section-title">الجهات التي تقدم هذه الخدمة</h2>
                <span class="section-badge">{{ $service->governments->count() }} جهة</span>
            </div>

            @if ($service->governments->count() > 0)
                <div class="governments-grid">
                    @foreach ($service->governments as $government)
                        @php
                            $pivot = $government->pivot;
                            $uniqueId = 'gov_' . $government->id;
                            $govImages = $government->images ?? [];
                            $firstImage = is_array($govImages) && count($govImages) > 0 ? $govImages[0] : null;
                        @endphp
                        <div class="government-card">
                            @if ($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" loading="lazy" class="card-img"
                                    alt="{{ $government->name }}">
                            @else
                                <div class="card-img-placeholder">
                                    <i class="fas fa-building"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <h3 class="government-name">
                                    <a href="{{ route('governments.show', $government->id) }}">{{ $government->name }}</a>
                                </h3>

                                <div class="quick-badges">
                                    @if ($pivot->price)
                                        <span class="quick-badge price"><i class="fas fa-tag"></i>
                                            {{ $pivot->price }}</span>
                                    @endif
                                    @if ($pivot->processing_time)
                                        <span class="quick-badge time"><i class="fas fa-hourglass-half"></i>
                                            {{ $pivot->processing_time }}</span>
                                    @endif
                                </div>

                                <button class="btn btn-sm btn-outline-primary rounded-pill w-100 toggle-details-btn"
                                    onclick="toggleDetails('{{ $uniqueId }}')">
                                    <i class="fas fa-chevron-down" id="icon_{{ $uniqueId }}"></i>
                                    <span id="text_{{ $uniqueId }}">عرض التفاصيل</span>
                                </button>

                                <div class="service-details" id="details_{{ $uniqueId }}" style="display: none;">
                                    @if ($pivot->description)
                                        <div class="detail-row">
                                            <div class="detail-label">📄 وصف الخدمة لهذه الجهة</div>
                                            <div class="detail-value">{{ $pivot->description }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->office_location)
                                        <div class="detail-row">
                                            <div class="detail-label">📍 موقع التقديم</div>
                                            <div class="detail-value">{{ $pivot->office_location }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->required_documents)
                                        <div class="detail-row">
                                            <div class="detail-label">📄 الأوراق المطلوبة</div>
                                            <div class="detail-value" style="white-space: pre-line;">
                                                {{ $pivot->required_documents }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->steps)
                                        <div class="detail-row">
                                            <div class="detail-label">📋 الإجراءات خطوة بخطوة</div>
                                            <div class="detail-value" style="white-space: pre-line;">{{ $pivot->steps }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($pivot->conditions)
                                        <div class="detail-row">
                                            <div class="detail-label">📜 الشروط</div>
                                            <div class="detail-value" style="white-space: pre-line;">
                                                {{ $pivot->conditions }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->notes)
                                        <div class="detail-row">
                                            <div class="detail-label">📝 ملاحظات إضافية</div>
                                            <div class="detail-value" style="white-space: pre-line;">{{ $pivot->notes }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($pivot->contact_number)
                                        <div class="detail-row">
                                            <div class="detail-label">📞 رقم الاتصال الخاص</div>
                                            <div class="detail-value"><a
                                                    href="tel:{{ $pivot->contact_number }}">{{ $pivot->contact_number }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($pivot->work_hours)
                                        <div class="detail-row">
                                            <div class="detail-label">🕐 ساعات العمل</div>
                                            <div class="detail-value">{{ $pivot->work_hours }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->requires_appointment)
                                        <div class="detail-row">
                                            <div class="detail-label">📅 يتطلب حجز مسبق</div>
                                            <div class="detail-value">
                                                نعم @if ($pivot->appointment_phone)
                                                    - <a
                                                        href="tel:{{ $pivot->appointment_phone }}">{{ $pivot->appointment_phone }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if ($pivot->doctor_specialist)
                                        <div class="detail-row">
                                            <div class="detail-label">👨‍⚕️ الطبيب المختص</div>
                                            <div class="detail-value">{{ $pivot->doctor_specialist }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->hospital_stay_duration)
                                        <div class="detail-row">
                                            <div class="detail-label">🏥 مدة التنويم المتوقعة</div>
                                            <div class="detail-value">{{ $pivot->hospital_stay_duration }}</div>
                                        </div>
                                    @endif
                                    @if ($pivot->emergency_notes)
                                        <div class="detail-row">
                                            <div class="detail-label">🚨 ملاحظات للطوارئ</div>
                                            <div class="detail-value" style="white-space: pre-line;">
                                                {{ $pivot->emergency_notes }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="gov-footer">
                                    <div class="gov-contact">
                                        <span><i class="fas fa-phone-alt"></i>
                                            {{ $government->contact_number ?? 'غير متوفر' }}</span>
                                        @if ($government->address)
                                            <span><i class="fas fa-map-marker-alt"></i>
                                                {{ Str::limit($government->address, 25) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('governments.show', $government->id) }}"
                                        class="btn btn-primary w-100 rounded-pill">
                                        <i class="fas fa-info-circle"></i> تفاصيل الجهة
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center py-5 bg-white rounded-4 border">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد جهات تقدم هذه الخدمة حالياً</p>
                </div>
            @endif

            <!-- Related Services -->
            @if (isset($relatedServices) && $relatedServices->count() > 0)
                <div class="section-header mt-5">
                    <h2 class="section-title">خدمات قد تهمك</h2>
                </div>
                <div class="related-grid">
                    @foreach ($relatedServices->take(4) as $related)
                        <a href="{{ route('services.show', $related->id) }}" class="related-card">
                            <div class="related-icon">
                                @if ($related->icon_image)
                                    <img src="{{ asset('storage/' . $related->icon_image) }}" loading="lazy"
                                        style="width: 30px; height: 30px; object-fit: contain;">
                                @else
                                    @php
                                        $categoryIcons = [
                                            1 => 'fa-hospital',
                                            2 => 'fa-shield-alt',
                                            3 => 'fa-envelope',
                                            4 => 'fa-id-card',
                                            5 => 'fa-passport',
                                        ];
                                        $catId = $related->government_category_id ?? 0;
                                        $icon = $categoryIcons[$catId] ?? 'fa-concierge-bell';
                                    @endphp
                                    <i class="fas {{ $icon }}"></i>
                                @endif
                            </div>
                            <div class="related-name">{{ Str::limit($related->name, 30) }}</div>
                            <div class="related-stats">
                                <small class="text-muted">{{ $related->governments->count() }} جهة</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal معرض الصور -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>معرض الصور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="galleryModalImage" src="" class="img-fluid" style="max-height: 70vh;">
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-secondary" onclick="changeImage(-1)">السابق</button>
                    <button class="btn btn-primary" onclick="changeImage(1)">التالي</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal الوصف الكامل -->
    <div class="modal fade" id="fullDescriptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>وصف {{ $service->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="white-space: pre-wrap; line-height: 1.8;">{{ $service->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/favorite.js') }}"></script>
    <script>
        let galleryImages = [];
        let currentGalleryIndex = 0;

        @if (count($images) > 0)
            galleryImages = {!! json_encode(
                array_map(function ($img) {
                    return asset('storage/' . $img);
                }, $images),
            ) !!};

            window.openGallery = function(index) {
                currentGalleryIndex = index;
                document.getElementById('galleryModalImage').src = galleryImages[index];
                new bootstrap.Modal(document.getElementById('galleryModal')).show();
            };

            window.changeImage = function(direction) {
                currentGalleryIndex = (currentGalleryIndex + direction + galleryImages.length) % galleryImages.length;
                document.getElementById('galleryModalImage').src = galleryImages[currentGalleryIndex];
            };
        @endif

        window.openFullDescriptionModal = function() {
            new bootstrap.Modal(document.getElementById('fullDescriptionModal')).show();
        };

        window.toggleDetails = function(id) {
            const details = document.getElementById(`details_${id}`);
            const icon = document.getElementById(`icon_${id}`);
            const text = document.getElementById(`text_${id}`);

            if (details.style.display === 'none' || details.style.display === '') {
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
        };

        // Favorite button
        document.getElementById('favoriteBtn')?.addEventListener('click', function() {
            fetch('{{ route('favorite.service.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: {{ $service->id }},
                        type: 'service'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.favorited) {
                        this.classList.add('active');
                        this.innerHTML = '<i class="fas fa-heart"></i><span>تمت الإضافة</span>';
                    } else {
                        this.classList.remove('active');
                        this.innerHTML = '<i class="fas fa-heart"></i><span>أضف للمفضلة</span>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endpush
