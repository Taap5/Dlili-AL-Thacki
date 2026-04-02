@extends('layouts.app')

@section('title', 'من نحن - دليلي الذكي')

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

    /* قسم الهيدر */
    .hero-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 150%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 60%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 16px;
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.6s ease;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.6s ease 0.1s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* عناوين الأقسام */
    .section-title {
        text-align: center;
        margin: 50px 0 40px;
    }

    .section-title h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        position: relative;
        display: inline-block;
        margin-bottom: 16px;
    }

    .section-title h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 50%;
        transform: translateX(50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    .section-title p {
        color: var(--text-muted);
        font-size: 1rem;
        margin-top: 20px;
    }

    /* بطاقات الفريق */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 32px;
        margin-bottom: 60px;
    }

    .team-card {
        background: white;
        border-radius: 28px;
        padding: 32px 24px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        position: relative;
        overflow: hidden;
    }

    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.2);
        border-color: rgba(47, 62, 158, 0.1);
    }

    .team-card:hover::before {
        transform: scaleX(1);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .team-card:hover .team-avatar {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scale(1.05);
    }

    .team-avatar i {
        font-size: 56px;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .team-card:hover .team-avatar i {
        color: white;
    }

    .team-card h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .team-role {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 16px;
        display: inline-block;
        background: #f0f4ff;
        padding: 4px 16px;
        border-radius: 30px;
    }

    .team-bio {
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .team-social {
        display: flex;
        justify-content: center;
        gap: 12px;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        background: #f0f4ff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
    }

    /* بطاقة المشروع */
    .project-card {
        background: linear-gradient(135deg, #ffffff, #fef9f0);
        border-radius: 28px;
        padding: 40px;
        text-align: center;
        margin-bottom: 60px;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    .project-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .project-icon i {
        font-size: 36px;
        color: var(--primary);
    }

    .project-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .project-card p {
        color: var(--text-muted);
        line-height: 1.6;
        max-width: 700px;
        margin: 0 auto;
    }

    .project-year {
        display: inline-block;
        margin-top: 20px;
        padding: 6px 20px;
        background: #f0f4ff;
        border-radius: 30px;
        color: var(--primary);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* إحصائيات */
    .stats-section {
        background: linear-gradient(135deg, #f8f9fa, #fef9f0);
        border-radius: 28px;
        padding: 50px 30px;
        margin-bottom: 60px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 30px;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: rgba(47, 62, 158, 0.2);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1.2;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 8px;
    }

    /* معلومات الاتصال */
    .contact-info {
        text-align: center;
        margin-top: 40px;
        padding: 30px;
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #f0f0f0;
    }

    .contact-info p {
        margin-bottom: 8px;
    }

    .contact-info a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s;
    }

    .contact-info a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .university-info {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    /* استجابة للهواتف */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            padding: 0 20px;
        }

        .section-title h2 {
            font-size: 1.5rem;
        }

        .team-grid {
            gap: 20px;
        }

        .team-card {
            padding: 24px 20px;
        }

        .team-avatar {
            width: 100px;
            height: 100px;
        }

        .team-avatar i {
            font-size: 48px;
        }

        .project-card {
            padding: 30px 20px;
        }

        .project-card h3 {
            font-size: 1.3rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-number {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- قسم الهيدر -->
<div class="hero-section">
    <div class="container">
        <h1 class="hero-title">من نحن</h1>
        <p class="hero-subtitle">فريق عمل دليلي الذكي – شباب يمني يسعى لخدمة وطنه</p>
    </div>
</div>

<div class="container py-4">
    <!-- فريق العمل -->
    <div class="section-title">
        <h2>فريق العمل</h2>
        <p>شباب طموح يسعى لتقديم الأفضل</p>
    </div>
    <div class="team-grid">
        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3>أصيل العامري</h3>
            <div class="team-role">باحث ومطور رئيسي</div>
            <p class="team-bio">
                مسؤول عن تطوير النظام، تصميم قاعدة البيانات، وتكامل واجهات البرمجة (APIs). لديه شغف كبير بتقنيات الويب الحديثة.
            </p>
            <div class="team-social">
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
            </div>
        </div>

        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3>إياد الصلوي</h3>
            <div class="team-role">باحث ومطور</div>
            <p class="team-bio">
                مسؤول عن تصميم واجهات المستخدم (UI/UX)، تحسين تجربة المستخدم، وجمع البيانات الميدانية. يهتم بالتصميم والإبداع.
            </p>
            <div class="team-social">
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </div>

    <!-- المشرف الأكاديمي -->
    <div class="section-title">
        <h2>الإشراف الأكاديمي</h2>
        <p>بإشراف نخبة من الأساتذة المتخصصين</p>
    </div>
    <div class="team-grid" style="justify-content: center;">
        <div class="team-card" style="max-width: 380px; margin: 0 auto;">
            <div class="team-avatar">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <h3>د. إسماعيل الأحمد</h3>
            <div class="team-role">مشرف المشروع</div>
            <p class="team-bio">
                أستاذ في قسم تقنية المعلومات، جامعة السعيدة. أشرف على جميع مراحل المشروع من التحليل إلى التنفيذ، وقدم الدعم العلمي والتقني للفريق.
            </p>
        </div>
    </div>

    <!-- عن المشروع -->
    <div class="section-title">
        <h2>عن المشروع</h2>
        <p>رؤيتنا ورسالتنا</p>
    </div>
    <div class="project-card">
        <div class="project-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>مشروع تخرج بكالوريوس</h3>
        <p>
            تم إنجاز هذا المشروع كجزء من متطلبات نيل شهادة البكالوريوس في تخصص تقنية المعلومات<br>
            بكلية الهندسة وتقنية المعلومات، جامعة السعيدة.
        </p>
        <div class="project-year">
            <i class="fas fa-calendar-alt me-1"></i> 2026
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="section-title">
        <h2>إنجازاتنا</h2>
        <p>أرقام تعكس حجم العمل</p>
    </div>
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Government::count() }}</div>
                <div class="stat-label">جهة حكومية</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\OfferService::count() }}</div>
                <div class="stat-label">خدمة متوفرة</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\User::count() }}</div>
                <div class="stat-label">مستخدم مسجل</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Review::count() }}</div>
                <div class="stat-label">تقييم</div>
            </div>
        </div>
    </div>

    <!-- معلومات التواصل -->
    <div class="contact-info">
        <p>
            <i class="fas fa-envelope text-primary me-2"></i>
            <a href="mailto:info@dalili.com">info@dalili.com</a>
        </p>
        <div class="university-info">
            <p class="text-muted small mb-0">
                <i class="fas fa-university me-1"></i>
                جامعة السعيدة - كلية الهندسة وتقنية المعلومات
            </p>
            <p class="text-muted small mt-2">
                <i class="fas fa-map-marker-alt me-1"></i>
                اليمن - صنعاء
            </p>
        </div>
    </div>
</div>
@endsection
