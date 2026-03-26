@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<style>
    main {
        padding-top: 100px !important;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 42px;
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        margin: 5px 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 13px;
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin: 25px 0 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eee;
    }

    .activity-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .activity-card:hover {
        background: #fafafa;
        border-color: #ddd;
    }

    .activity-info {
        flex: 1;
    }

    .activity-title {
        font-weight: bold;
        color: #2f3e9e;
        text-decoration: none;
    }

    .activity-title:hover {
        text-decoration: underline;
    }

    .activity-meta {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    .activity-rating {
        color: #ffc107;
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 20px;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .btn-sm-outline {
        background: none;
        border: 1px solid #2f3e9e;
        color: #2f3e9e;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-sm-outline:hover {
        background: #2f3e9e;
        color: white;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="container py-5">
    <!-- رسالة ترحيب -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-user-circle fa-3x text-primary"></i>
                <div>
                    <h3 class="fw-bold mb-0">مرحباً {{ Auth::user()->user_name }}</h3>
                    <p class="text-muted mb-0">مرحباً بك في لوحة التحكم الخاصة بك</p>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star text-warning"></i></div>
            <div class="stat-number">{{ Auth::user()->reviews->count() }}</div>
            <div class="stat-label">تقييماتك</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-heart text-danger"></i></div>
            <div class="stat-number">{{ Auth::user()->favoriteGovernments->count() + Auth::user()->favoriteServices->count() }}</div>
            <div class="stat-label">المفضلات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-alt text-primary"></i></div>
            <div class="stat-number">{{ Auth::user()->created_at->diffForHumans() }}</div>
            <div class="stat-label">عضو منذ</div>
        </div>
    </div>

    <!-- آخر النشاطات (تقييمات + مفضلات) -->
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
                                <span class="mx-2">•</span>
                                {{ $review->created_at->diffForHumans() }}
                            </div>
                            <div class="text-muted small mt-1">
                                {{ Str::limit($review->comment ?? 'لا يوجد تعليق', 60) }}
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(Auth::user()->reviews->count() > 3)
                    <div class="text-center mt-2">
                        <a href="{{ route('my.reviews') }}" class="btn-sm-outline">عرض جميع التقييمات</a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-star fa-2x mb-2 text-muted"></i>
                    <p class="mb-2">لا توجد تقييمات بعد</p>
                    <a href="/" class="btn-sm-outline">استعرض الجهات</a>
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
                                    <i class="fas fa-building text-primary me-1"></i>
                                    {{ $fav['name'] }}
                                </a>
                            @else
                                <a href="{{ route('services.show', $fav['id']) }}" class="activity-title">
                                    <i class="fas fa-concierge-bell text-primary me-1"></i>
                                    {{ $fav['name'] }}
                                </a>
                            @endif
                            <div class="activity-meta">
                                <i class="far fa-heart text-danger"></i>
                                <span class="mx-1">أضيف للمفضلة</span>
                                {{ \Carbon\Carbon::parse($fav['created_at'])->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-2">
                    <a href="{{ route('favorites') }}" class="btn-sm-outline">عرض جميع المفضلات</a>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-heart fa-2x mb-2 text-muted"></i>
                    <p class="mb-2">لا توجد مفضلات بعد</p>
                    <a href="/" class="btn-sm-outline">استعرض الجهات والخدمات</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
