@extends('layouts.app')

@section('title', $offer->title . ' - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-light: rgba(47, 62, 158, 0.1);
    }

    .offer-detail-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .offer-detail-page::before {
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

    @keyframes floatBg {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
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

    /* البطاقة الرئيسية */
    .detail-card {
        background: white;
        border-radius: 32px;
        border: 1px solid var(--border-light);
        overflow: hidden;
        position: relative;
        z-index: 2;
    }

    .offer-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary), var(--primary-light));
        padding: 2rem;
        text-align: center;
        color: white;
    }

    .offer-icon-display {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .offer-icon-display i {
        font-size: 3rem;
        color: white;
    }

    .offer-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .offer-type-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.3rem 1rem;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }

    .offer-content {
        padding: 2rem;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .info-section h5 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-section h5 i {
        color: var(--primary);
    }

    .info-section p {
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 0.9rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
    }

    .info-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary);
    }

    .info-card i {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--primary);
    }

    .info-card .info-label {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .info-card .info-value {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .btn-call {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        border-radius: 60px;
        padding: 1rem;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }

    .btn-call:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-outline-custom {
        background: transparent;
        border: 2px solid var(--primary);
        border-radius: 60px;
        padding: 0.8rem 1.5rem;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s;
        flex: 1;
    }

    .btn-outline-custom:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary-custom {
        background: #f1f5f9;
        border: none;
        border-radius: 60px;
        padding: 0.8rem 1.5rem;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s;
        flex: 1;
    }

    .btn-secondary-custom:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: var(--text-dark);
    }

    @media (max-width: 768px) {
        .offer-detail-page {
            padding: 1rem 0;
        }

        .offer-header {
            padding: 1.5rem;
        }

        .offer-header h1 {
            font-size: 1.4rem;
        }

        .offer-content {
            padding: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="offer-detail-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb -->
                <div class="breadcrumb-custom">
                    <a href="{{ route('home') }}">الرئيسية</a>
                    <span class="separator">/</span>
                    <a href="{{ route('offers.index') }}">العروض</a>
                    <span class="separator">/</span>
                    <span class="current">{{ $offer->title }}</span>
                </div>

                <div class="detail-card">
                    <!-- رأس العرض -->
                    <div class="offer-header">
                        <div class="offer-icon-display">
                            <i class="{{ $offer->icon ?? 'fas fa-gift' }}"></i>
                        </div>
                        <h1>{{ $offer->title }}</h1>
                        <span class="offer-type-badge">{{ $types[$offer->offer_type] ?? $offer->offer_type }}</span>
                    </div>

                    <!-- محتوى العرض -->
                    <div class="offer-content">
                        <!-- وصف العرض -->
                        <div class="info-section">
                            <h5><i class="fas fa-info-circle"></i> وصف العرض</h5>
                            <p>{{ $offer->description }}</p>
                        </div>

                        <!-- الفئة المستهدفة -->
                        @if($offer->target_audience)
                            <div class="info-section">
                                <h5><i class="fas fa-users"></i> الفئة المستهدفة</h5>
                                <p>{{ $offer->target_audience }}</p>
                            </div>
                        @endif

                        <!-- الشروط والأحكام -->
                        @if($offer->terms)
                            <div class="info-section">
                                <h5><i class="fas fa-file-contract"></i> الشروط والأحكام</h5>
                                <p>{{ $offer->terms }}</p>
                            </div>
                        @endif

                        <!-- معلومات إضافية -->
                        <div class="info-grid">
                            <div class="info-card">
                                <i class="fas fa-building"></i>
                                <div>
                                    <div class="info-label">الجهة المقدمة</div>
                                    <div class="info-value">{{ $offer->government->name }}</div>
                                </div>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <div class="info-label">مدة العرض</div>
                                    <div class="info-value">
                                        @if($offer->is_permanent)
                                            عرض مستمر
                                        @else
                                            {{ $offer->start_date ?: 'غير محدد' }} - {{ $offer->end_date ?: 'غير محدد' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الاتصال -->
                        @if($offer->contact_number)
                            <a href="tel:{{ $offer->contact_number }}" class="btn-call">
                                <i class="fas fa-phone-alt"></i> للاستفسار: {{ $offer->contact_number }}
                            </a>
                        @endif

                        <!-- أزرار الإجراءات -->
                        <div class="action-buttons">
                            <a href="{{ route('governments.show', $offer->government->id) }}" class="btn-outline-custom">
                                <i class="fas fa-building"></i> زيارة صفحة الجهة
                            </a>
                            <a href="{{ route('offers.index') }}" class="btn-secondary-custom">
                                <i class="fas fa-arrow-right"></i> جميع العروض
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
