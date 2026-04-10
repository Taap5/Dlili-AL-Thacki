@extends('layouts.app')

@section('title', 'الصفحة الرئيسية - دليلي الذكي')

@section('content')
<style>
    /* ===== تصميم فريد بخلفية فاتحة ===== */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap');

    * {
        font-family: 'Cairo', sans-serif;
    }

    :root {
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --accent-gradient-start: #2f3e9e;
        --accent-gradient-end: #5a6fc9;
        --glow-soft: rgba(47, 62, 158, 0.15);
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-light: rgba(47, 62, 158, 0.12);
        --card-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.12);
    }

    /* ===== خلفية ديناميكية فاتحة ===== */
    .unique-hero {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding: 4rem 0;
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 50%, #ffffff 100%);
    }

    /* كرات متحركة في الخلفية - بألوان فاتحة */
    .bg-particle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.4;
        filter: blur(70px);
        animation: floatParticle 20s infinite ease-in-out;
    }

    .particle-1 {
        width: 400px;
        height: 400px;
        top: -100px;
        right: -100px;
        animation-delay: 0s;
        background: radial-gradient(circle, rgba(47, 62, 158, 0.15), transparent);
    }
    .particle-2 {
        width: 300px;
        height: 300px;
        bottom: -50px;
        left: -50px;
        animation-delay: 5s;
        background: radial-gradient(circle, rgba(90, 111, 201, 0.12), transparent);
    }
    .particle-3 {
        width: 250px;
        height: 250px;
        top: 40%;
        left: 30%;
        animation-delay: 10s;
        background: radial-gradient(circle, rgba(47, 62, 158, 0.1), transparent);
    }

    @keyframes floatParticle {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    /* ===== النص الرئيسي ===== */
    .gradient-text {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.2rem;
        color: var(--text-muted);
        max-width: 600px;
        margin-bottom: 2rem;
    }

    /* ===== شريط البحث الفريد ===== */
    .unique-search-wrapper {
        max-width: 700px;
        margin: 0 auto;
        position: relative;
    }

    .unique-search-wrapper::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--primary));
        border-radius: 60px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: -1;
    }

    .unique-search-wrapper:hover::before {
        opacity: 0.5;
    }

    .unique-search-wrapper .search-bar {
        background: white !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 60px !important;
        padding: 12px 20px !important;
        color: var(--text-dark) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .unique-search-wrapper .search-bar:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px var(--glow-soft) !important;
    }

    /* ===== البطاقات المغناطيسية - نسخة فاتحة ===== */
    .magnetic-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.8rem;
        margin-top: 3.5rem;
    }

    .magnetic-card {
        background: var(--bg-white);
        border-radius: 28px;
        padding: 1.8rem;
        transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        cursor: pointer;
        position: relative;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
    }

    .magnetic-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(47, 62, 158, 0.05), transparent);
        transition: left 0.5s;
    }

    .magnetic-card:hover::before {
        left: 100%;
    }

    .magnetic-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: var(--primary);
        box-shadow: 0 25px 45px -12px rgba(47, 62, 158, 0.25);
    }

    .card-icon-glow {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, rgba(47, 62, 158, 0.08), rgba(90, 111, 201, 0.08));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.2rem;
        transition: all 0.3s;
    }

    .magnetic-card:hover .card-icon-glow {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: rotate(5deg) scale(1.1);
    }

    .card-icon-glow i {
        font-size: 2rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .magnetic-card:hover .card-icon-glow i {
        color: white;
    }

    .magnetic-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .magnetic-card p {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .card-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .stat-badge {
        background: rgba(47, 62, 158, 0.08);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        color: var(--primary);
        font-weight: 500;
    }

    .card-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .card-link:hover {
        color: var(--primary-light);
        transform: translateX(-5px);
        display: inline-block;
    }

    /* ===== قسم المميزات ===== */
    .features-section {
        padding: 4rem 0;
        background: var(--bg-light);
        position: relative;
    }

    .section-badge {
        display: inline-block;
        background: rgba(47, 62, 158, 0.1);
        padding: 6px 16px;
        border-radius: 40px;
        color: var(--primary);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .features-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2rem;
    }

    .feature-item {
        background: var(--bg-white);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
    }

    .feature-item:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: 0 15px 30px rgba(47, 62, 158, 0.1);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-icon i {
        font-size: 1.8rem;
        color: white;
    }

    .feature-item h4 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .feature-item p {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* ===== شريط النصائح ===== */
    .chips-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 2rem;
    }

    .chip-glow {
        background: white;
        padding: 10px 22px;
        border-radius: 50px;
        text-decoration: none;
        color: var(--primary);
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid var(--border-light);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .chip-glow:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(47, 62, 158, 0.2);
        border-color: transparent;
    }

    /* ===== استجابة ===== */
    @media (max-width: 768px) {
        .gradient-text {
            font-size: 2rem;
        }
        .hero-description {
            font-size: 0.9rem;
        }
        .features-title {
            font-size: 1.5rem;
        }
        .magnetic-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
    /* ===== تحسينات إضافية للهواتف ===== */
@media (max-width: 768px) {
    /* تقليل المسافات */
    .unique-hero {
        padding: 2rem 0;
        min-height: auto;
    }

    /* النص يصبح أصغر ومتمركز */
    .gradient-text {
        font-size: 1.8rem;
    }

    .hero-description {
        font-size: 0.85rem;
        padding: 0 1rem;
    }

    /* شريط البحث يأخذ عرض كامل */
    .unique-search-wrapper {
        padding: 0 1rem;
    }

    .unique-search-wrapper .search-bar {
        font-size: 14px;
        padding: 10px 16px !important;
    }

    /* البطاقات تصبح بعرض كامل */
    .magnetic-grid {
        gap: 1rem;
        margin-top: 2rem;
        padding: 0 0.5rem;
    }

    .magnetic-card {
        padding: 1.2rem;
    }

    .card-icon-glow {
        width: 50px;
        height: 50px;
    }

    .card-icon-glow i {
        font-size: 1.5rem;
    }

    .magnetic-card h3 {
        font-size: 1.1rem;
    }

    .magnetic-card p {
        font-size: 0.75rem;
    }

    /* قسم المميزات */
    .features-section {
        padding: 2rem 0;
    }

    .features-title {
        font-size: 1.3rem;
    }

    .feature-item {
        padding: 1rem;
    }

    .feature-icon {
        width: 45px;
        height: 45px;
    }

    .feature-icon i {
        font-size: 1.3rem;
    }

    .feature-item h4 {
        font-size: 0.85rem;
    }

    .feature-item p {
        font-size: 0.7rem;
    }

    /* النصائح (chips) */
    .chips-container {
        gap: 8px;
        margin-top: 1.5rem;
        padding: 0 0.5rem;
    }

    .chip-glow {
        padding: 6px 14px;
        font-size: 0.7rem;
    }

    /* تعطيل التأثير المغناطيسي على الهاتف */
    .magnetic-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .magnetic-card:hover {
        transform: translateY(-5px);
    }

    /* إزالة تأثير rotate على الهاتف */
    .magnetic-card {
        transform: none !important;
    }
}

/* للهواتف الصغيرة جداً (أقل من 400px) */
@media (max-width: 480px) {
    .gradient-text {
        font-size: 1.5rem;
    }

    .features-title {
        font-size: 1.2rem;
    }

    .col-6 {
        width: 100%;
    }

    /* قسم المميزات يصبح عمود واحد */
    .features-section .row .col-6 {
        width: 100%;
    }
}
</style>

<!-- قسم البطل (Hero) -->
<section class="unique-hero">
    <div class="bg-particle particle-1"></div>
    <div class="bg-particle particle-2"></div>
    <div class="bg-particle particle-3"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="text-center">
            <div class="gradient-text">دليلي الذكي</div>
            <p class="hero-description mx-auto">
                منصة الخدمات الحكومية المتكاملة. وصول ذكي، تجربة سلسة، مستقبل رقمي.
            </p>

            <!-- شريط البحث -->
            <div class="unique-search-wrapper">
                <x-search-bar />
            </div>

            <!-- نصائح البحث -->
            <div class="chips-container">
                @foreach ($searchSuggestions as $suggestion)
                    @if ($suggestion['type'] === 'government')
                        <a href="{{ route('governments.show', $suggestion['id']) }}" class="chip-glow">
                            <i class="fas fa-building"></i> {{ $suggestion['name'] }}
                        </a>
                    @elseif($suggestion['type'] === 'service')
                        <a href="{{ route('services.show', $suggestion['id']) }}" class="chip-glow">
                            <i class="fas fa-concierge-bell"></i> {{ $suggestion['name'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- البطاقات المغناطيسية للتصنيفات -->
        <div class="magnetic-grid">
            @foreach ($categories as $category)
                <div class="magnetic-card" onclick="window.location='{{ route('categories.show', $category->id) }}'">
                    <div class="card-icon-glow">
                        <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                    </div>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ Str::limit($category->description, 70) }}</p>
                    <div class="card-stats">
                        <span class="stat-badge">
                            <i class="fas fa-building me-1"></i> {{ $category->governments_count ?? $category->governments->count() }} جهة
                        </span>
                        <a href="{{ route('categories.show', $category->id) }}" class="card-link">
                            استعرض <i class="fas fa-arrow-left ms-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم المميزات -->
<section class="features-section">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">مميزاتنا</span>
            <h2 class="features-title">لماذا <span style="color: var(--primary);">دليلي الذكي</span>؟</h2>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>سرعة فائقة</h4>
                    <p>إنجاز المعاملات في ثوانٍ</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>حماية متطورة</h4>
                    <p>أمان مطلق لبياناتك</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>خدمة دائمة</h4>
                    <p>متاح 24/7 طوال الأسبوع</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>دعم فوري</h4>
                    <p>فريق دعم على مدار الساعة</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- زر الطوارئ والمودال -->
@include('components.emergency-button')
@include('components.emergency-modal')

@push('scripts')
<script src="{{ asset('js/emergency.js') }}"></script>
<script>
    // تأثير البطاقات المغناطيسية
    document.querySelectorAll('.magnetic-card').forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 25;
            const rotateY = (centerX - x) / 25;
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px) scale(1.02)`;
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

</script>
@endpush
@push('scripts')
<script src="{{ asset('js/emergency.js') }}"></script>
<script>
    // تفعيل التأثير المغناطيسي فقط على سطح المكتب (وليس على اللمس)
    if (window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('.magnetic-card').forEach(card => {
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 25;
                const rotateY = (centerX - x) / 25;
                this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px) scale(1.02)`;
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    }
</script>
@endpush
@endsection
