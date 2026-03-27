@extends('layouts.app')

@section('title', 'من نحن - دليلي الذكي')

@section('content')
<style>
    .team-header {
        background: linear-gradient(135deg, #2f3e9e 0%, #5a6fc9 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }

    .team-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .team-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 12px;
        text-align: center;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 3px;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .team-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .team-avatar i {
        font-size: 60px;
        color: white;
    }

    .team-card h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .team-card .role {
        color: #2f3e9e;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .team-card .bio {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .team-social {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        background: #f0f2ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2f3e9e;
        text-decoration: none;
        transition: all 0.3s;
    }

    .social-icon:hover {
        background: #2f3e9e;
        color: white;
        transform: translateY(-3px);
    }

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-top: 30px;
    }

    .info-icon {
        width: 70px;
        height: 70px;
        background: #f0f2ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .info-icon i {
        font-size: 30px;
        color: #2f3e9e;
    }

    .info-card h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .info-card p {
        color: #666;
        margin-bottom: 15px;
    }

    .info-card a {
        color: #2f3e9e;
        text-decoration: none;
        font-weight: 500;
    }

    .info-card a:hover {
        text-decoration: underline;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .stat-item {
        background: #f8f9ff;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #2f3e9e;
    }

    .stat-label {
        color: #666;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .team-header h1 {
            font-size: 1.8rem;
        }
        .section-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="team-header">
    <div class="container">
        <h1>من نحن</h1>
        <p>فريق عمل دليلي الذكي – شباب يمني يسعى لخدمة وطنه</p>
    </div>
</div>

<div class="container py-4">
    <!-- فريق العمل -->
    <div class="section-title">فريق العمل</div>
    <div class="team-grid">
        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3>أصيل العامري</h3>
            <div class="role">باحث ومطور رئيسي</div>
            <p class="bio">
                مسؤول عن تطوير النظام، تصميم قاعدة البيانات، وتكامل واجهات البرمجة (APIs).
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
            <div class="role">باحث ومطور</div>
            <p class="bio">
                مسؤول عن تصميم واجهات المستخدم (UI/UX)، تحسين تجربة المستخدم، وجمع البيانات الميدانية.
            </p>
            <div class="team-social">
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </div>

    <!-- المشرف الأكاديمي -->
    <div class="section-title">الإشراف الأكاديمي</div>
    <div class="team-grid">
        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <h3>د. إسماعيل الأحمد</h3>
            <div class="role">مشرف المشروع</div>
            <p class="bio">
                أستاذ في قسم تقنية المعلومات، جامعة السعيدة. أشرف على جميع مراحل المشروع من التحليل إلى التنفيذ.
            </p>
        </div>
    </div>

    <!-- عن المشروع -->
    <div class="section-title">عن المشروع</div>
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h4>مشروع تخرج بكالوريوس</h4>
        <p>
            تم إنجاز هذا المشروع كجزء من متطلبات نيل شهادة البكالوريوس في تخصص تقنية المعلومات<br>
            بكلية الهندسة وتقنية المعلومات، جامعة السعيدة.
        </p>
        <p class="text-muted small mt-2">
            <i class="fas fa-calendar-alt me-1"></i> 2026
        </p>
    </div>

    <!-- إحصائيات -->
    <div class="section-title">إنجازاتنا</div>
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

    <!-- روابط التواصل -->
    <div class="text-center mt-5 mb-4">
        <p class="text-muted">
            <i class="fas fa-envelope me-2"></i>
            <a href="mailto:info@dalili.com" class="text-decoration-none">info@dalili.com</a>
        </p>
        <p class="text-muted small">
            <i class="fas fa-university me-1"></i>
            جامعة السعيدة - كلية الهندسة وتقنية المعلومات
        </p>
    </div>
</div>
@endsection
