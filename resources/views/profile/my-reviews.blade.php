@extends('layouts.app')

@section('title', 'تقييماتي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --bg-light: #fef9f0;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
    }

    .reviews-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .page-header {
        margin-bottom: 32px;
        text-align: center;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        position: relative;
        display: inline-block;
        margin-bottom: 12px;
    }

    .page-title i {
        color: #ffc107;
        margin-left: 12px;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #ffc107, #ffb300);
        border-radius: 3px;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .stats-summary {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }

    .stat-badge {
        background: #fff;
        border-radius: 60px;
        padding: 10px 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .stat-badge i {
        font-size: 20px;
    }

    .stat-badge .stat-number {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-badge .stat-label {
        color: var(--text-muted);
        font-size: 13px;
    }

    .stat-badge.average i {
        color: #ffc107;
    }

    /* بطاقة التقييم */
    .review-card {
        background: #ffffff;
        border-radius: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        position: relative;
    }

    .review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        border-color: rgba(47, 62, 158, 0.15);
    }

    .review-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .review-card:hover::before {
        opacity: 1;
    }

    .review-header {
        padding: 20px 24px 0 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
    }

    .review-government {
        flex: 1;
    }

    .review-government h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .review-government h3 a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .review-government h3 a:hover {
        color: var(--primary);
    }

    .review-category {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f0f4ff;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        color: var(--primary);
        margin-top: 4px;
    }

    .review-rating {
        text-align: left;
    }

    .stars-large {
        font-size: 18px;
        color: #ffc107;
        margin-bottom: 4px;
    }

    .rating-number {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin-left: 6px;
    }

    .rating-text {
        font-size: 12px;
        color: var(--text-muted);
    }

    .review-comment {
        padding: 16px 24px;
        background: #fafafa;
        margin: 16px 24px;
        border-radius: 20px;
        position: relative;
    }

    .review-comment::before {
        content: '"';
        position: absolute;
        top: 8px;
        right: 16px;
        font-size: 32px;
        color: var(--primary);
        opacity: 0.2;
        font-family: serif;
    }

    .review-comment p {
        margin: 0;
        color: var(--text-dark);
        line-height: 1.6;
        font-size: 0.95rem;
        padding-right: 20px;
    }

    .review-footer {
        padding: 16px 24px 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        border-top: 1px solid #f0f0f0;
        background: #fff;
    }

    .review-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        font-size: 12px;
    }

    .review-date i {
        font-size: 12px;
    }

    .review-actions {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        background: transparent;
        border: 1px solid var(--primary);
        border-radius: 30px;
        padding: 6px 16px;
        color: var(--primary);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid #dc3545;
        border-radius: 30px;
        padding: 6px 16px;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-1px);
    }

    /* حالة عدم وجود بيانات */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 32px;
        border: 1px solid #f0f0f0;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #f0f2f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 40px;
        color: #ffc107;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .btn-explore {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 40px;
        padding: 10px 28px;
        color: white;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-explore:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(47, 62, 158, 0.3);
        color: white;
    }

    /* تنبيهات */
    .alert-custom {
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: none;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success-custom {
        background: #e8f5e9;
        color: #2e7d32;
        border-right: 4px solid #2e7d32;
    }

    @media (max-width: 768px) {
        .reviews-container {
            padding: 15px;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .stats-summary {
            gap: 12px;
        }

        .stat-badge {
            padding: 6px 16px;
        }

        .stat-badge .stat-number {
            font-size: 16px;
        }

        .review-header {
            padding: 16px 20px 0 20px;
            flex-direction: column;
        }

        .review-rating {
            text-align: right;
        }

        .review-comment {
            margin: 12px 20px;
            padding: 12px 16px;
        }

        .review-footer {
            padding: 12px 20px 16px 20px;
            flex-direction: column;
            align-items: stretch;
        }

        .review-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="reviews-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-star"></i>
            تقييماتي
        </h1>
        <p class="page-subtitle">جميع التقييمات التي قمت بإضافتها للجهات الحكومية</p>
    </div>

    @php
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
    @endphp

    <!-- إحصائيات سريعة -->
    <div class="stats-summary">
        <div class="stat-badge">
            <i class="fas fa-star text-warning"></i>
            <div>
                <span class="stat-number">{{ $totalReviews }}</span>
                <span class="stat-label">تقييم</span>
            </div>
        </div>
        @if($totalReviews > 0)
            <div class="stat-badge average">
                <i class="fas fa-chart-line"></i>
                <div>
                    <span class="stat-number">{{ number_format($averageRating, 1) }}</span>
                    <span class="stat-label">متوسط التقييم</span>
                </div>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($reviews->count() > 0)
        @foreach($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <div class="review-government">
                        <h3>
                            <a href="{{ route('governments.show', $review->government->id) }}">
                                {{ $review->government->name }}
                            </a>
                        </h3>
                        @if($review->government->category)
                            <div class="review-category">
                                <i class="fas fa-tag"></i>
                                {{ $review->government->category->name }}
                            </div>
                        @endif
                    </div>
                    <div class="review-rating">
                        <div class="stars-large">
                            <span class="rating-number">{{ $review->rating }}</span>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="rating-text">
                            @if($review->rating == 5)
                                ممتاز
                            @elseif($review->rating >= 4)
                                جيد جداً
                            @elseif($review->rating >= 3)
                                جيد
                            @elseif($review->rating >= 2)
                                مقبول
                            @else
                                يحتاج تحسين
                            @endif
                        </div>
                    </div>
                </div>

                @if($review->comment)
                    <div class="review-comment">
                        <p>{{ $review->comment }}</p>
                    </div>
                @endif

                <div class="review-footer">
                    <div class="review-date">
                        <i class="far fa-calendar-alt"></i>
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                    <div class="review-actions">
                        <a href="{{ route('governments.show', $review->government->id) }}" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                        <button class="btn-delete" onclick="confirmDelete({{ $review->id }})">
                            <i class="fas fa-trash-alt"></i>
                            حذف
                        </button>
                        <form id="delete-form-{{ $review->id }}" action="{{ route('my.reviews.destroy', $review->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-star"></i>
            </div>
            <h4>لا توجد تقييمات</h4>
            <p>لم تقم بإضافة أي تقييمات بعد. كن أول من يقيم!</p>
            <a href="/" class="btn-explore">
                <i class="fas fa-compass"></i>
                استعرض الجهات
            </a>
        </div>
    @endif
</div>

<script>
    function confirmDelete(reviewId) {
        if (confirm('هل أنت متأكد من حذف هذا التقييم؟ لا يمكن التراجع عن هذا الإجراء.')) {
            document.getElementById('delete-form-' + reviewId).submit();
        }
    }
</script>
@endsection
