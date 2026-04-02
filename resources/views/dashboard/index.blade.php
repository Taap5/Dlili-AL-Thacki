@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --secondary: #ffc107;
        --bg-light: #fef9f0;
        --bg-gray: #f8f9fa;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
    }

    main {
        padding-top: 100px !important;
    }

    /* بطاقة الترحيب */
    .welcome-card {
        background: linear-gradient(135deg, #ffffff 0%, #fef9f0 100%);
        border: none;
        border-radius: 28px;
        padding: 24px 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(47,62,158,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .welcome-avatar {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 16px;
        box-shadow: 0 8px 20px rgba(47, 62, 158, 0.2);
    }

    .welcome-avatar i {
        font-size: 32px;
        color: white;
    }

    .welcome-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .welcome-subtitle {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    /* بطاقات الإحصائيات */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(47, 62, 158, 0.08);
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(47, 62, 158, 0.12);
    }

    .stat-card:hover::after {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 12px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
    }

    .stat-icon i {
        font-size: 28px;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon i {
        color: white !important;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-dark);
        margin: 8px 0;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 500;
    }

    /* أقسام النشاطات */
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-dark);
        position: relative;
        padding-right: 16px;
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

    .section-title i {
        font-size: 1.2rem;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    /* بطاقات النشاطات */
    .activity-card {
        background: white;
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 12px;
        transition: all 0.2s;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .activity-card:hover {
        background: #fef9f0;
        border-color: rgba(47, 62, 158, 0.2);
        transform: translateX(-3px);
    }

    .activity-info {
        flex: 1;
    }

    .activity-title {
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .activity-title:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .activity-meta {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 8px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .activity-rating {
        color: #ffc107;
        font-size: 12px;
    }

    .activity-comment {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 8px;
        line-height: 1.4;
    }

    .btn-link-custom {
        background: none;
        border: 1px solid var(--primary);
        color: var(--primary);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-link-custom:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    /* حالة عدم وجود بيانات */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 24px;
        color: var(--text-muted);
        border: 1px solid #f0f0f0;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state p {
        margin-bottom: 16px;
    }

    /* استجابة للهواتف */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 20px;
        }

        .welcome-title {
            font-size: 1.3rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .grid-2 {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
        }

        .stat-icon i {
            font-size: 24px;
        }

        .stat-number {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .activity-card {
            padding: 14px;
        }

        .activity-title {
            font-size: 0.9rem;
        }
    }
</style>

<div class="container py-5">
    <!-- بطاقة الترحيب -->
    <div class="welcome-card">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="welcome-avatar">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle w-100 h-100 object-fit-cover">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div>
                <h1 class="welcome-title">مرحباً {{ Auth::user()->user_name }}</h1>
                <p class="welcome-subtitle">مرحباً بك في لوحة التحكم الخاصة بك. استعرض نشاطاتك وإحصائياتك.</p>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-star text-warning"></i>
            </div>
            <div class="stat-number">{{ Auth::user()->reviews->count() }}</div>
            <div class="stat-label">تقييماتك</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-heart text-danger"></i>
            </div>
            <div class="stat-number">{{ Auth::user()->favoriteGovernments->count() + Auth::user()->favoriteServices->count() }}</div>
            <div class="stat-label">المفضلات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt text-primary"></i>
            </div>
            <div class="stat-number">{{ Auth::user()->created_at->format('Y/m/d') }}</div>
            <div class="stat-label">عضو منذ</div>
        </div>
    </div>

    <!-- آخر النشاطات -->
    <div class="grid-2">
        <!-- آخر التقييمات -->
        <div>
            <div class="section-title">
                <i class="fas fa-star text-warning"></i>
                آخر تقييماتك
            </div>

            @if(Auth::user()->reviews->count() > 0)
                @foreach(Auth::user()->reviews->take(3) as $review)
                    <div class="activity-card">
                        <div class="activity-info">
                            <a href="{{ route('governments.show', $review->government_id) }}" class="activity-title">
                                <i class="fas fa-building"></i>
                                {{ $review->government->name }}
                            </a>
                            <div class="activity-meta">
                                <span class="activity-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span>•</span>
                                <span><i class="far fa-clock"></i> {{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <div class="activity-comment">
                                    "{{ Str::limit($review->comment, 80) }}"
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if(Auth::user()->reviews->count() > 3)
                    <div class="text-center mt-3">
                        <a href="{{ route('my.reviews') }}" class="btn-link-custom">
                            <i class="fas fa-arrow-left"></i>
                            عرض جميع التقييمات
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <p>لا توجد تقييمات بعد</p>
                    <a href="/" class="btn-link-custom">استعرض الجهات</a>
                </div>
            @endif
        </div>

        <!-- آخر المفضلات -->
        <div>
            <div class="section-title">
                <i class="fas fa-heart text-danger"></i>
                آخر المفضلات
            </div>

            @php
                $latestFavorites = collect();

                foreach(Auth::user()->favoriteGovernments->take(5) as $gov) {
                    $latestFavorites->push([
                        'type' => 'government',
                        'id' => $gov->id,
                        'name' => $gov->name,
                        'created_at' => $gov->pivot->created_at ?? now(),
                    ]);
                }

                foreach(Auth::user()->favoriteServices->take(5) as $service) {
                    $latestFavorites->push([
                        'type' => 'service',
                        'id' => $service->id,
                        'name' => $service->name,
                        'created_at' => $service->pivot->created_at ?? now(),
                    ]);
                }

                $latestFavorites = $latestFavorites->sortByDesc('created_at')->take(3);
            @endphp

            @if($latestFavorites->count() > 0)
                @foreach($latestFavorites as $fav)
                    <div class="activity-card">
                        <div class="activity-info">
                            @if($fav['type'] == 'government')
                                <a href="{{ route('governments.show', $fav['id']) }}" class="activity-title">
                                    <i class="fas fa-building"></i>
                                    {{ $fav['name'] }}
                                </a>
                            @else
                                <a href="{{ route('services.show', $fav['id']) }}" class="activity-title">
                                    <i class="fas fa-concierge-bell"></i>
                                    {{ $fav['name'] }}
                                </a>
                            @endif
                            <div class="activity-meta">
                                <i class="fas fa-heart text-danger"></i>
                                <span>أضيف للمفضلة • {{ \Carbon\Carbon::parse($fav['created_at'])->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-3">
                    <a href="{{ route('favorites') }}" class="btn-link-custom">
                        <i class="fas fa-arrow-left"></i>
                        عرض جميع المفضلات
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <p>لا توجد مفضلات بعد</p>
                    <a href="/" class="btn-link-custom">استعرض الجهات والخدمات</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
