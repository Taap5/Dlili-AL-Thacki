@extends('layouts.app')

@section('title', 'من نحن - دليلي الذكي')

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

    .team-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .team-page::before {
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

    .team-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
    }

    .team-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        animation: fadeInUp 0.6s ease;
    }

    .team-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        animation: fadeInUp 0.6s ease 0.1s both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 2rem 0 1.5rem;
        text-align: center;
        position: relative;
    }

    .section-title::after {
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

    .team-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
        height: 100%;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
        border-color: var(--primary);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .team-card:hover .team-avatar {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .team-card:hover .team-avatar i {
        color: white;
    }

    .team-avatar i {
        font-size: 3rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .team-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-dark);
    }

    .team-role {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .team-bio {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.6;
    }

    .team-social {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1rem;
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
        transition: all 0.3s;
    }

    .social-icon:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
    }

    .project-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
        border: 1px solid var(--border-light);
        transition: all 0.3s;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    .project-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .project-icon i {
        font-size: 2rem;
        color: var(--primary);
    }

    .project-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .project-year {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.3rem 1.2rem;
        background: #f0f4ff;
        border-radius: 40px;
        color: var(--primary);
        font-size: 0.85rem;
    }

    .stats-section {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        margin: 2rem 0;
        border: 1px solid var(--border-light);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .contact-info {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 20px;
        margin-top: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
    }

    .contact-info a {
        color: var(--primary);
        text-decoration: none;
    }

    .contact-info a:hover {
        text-decoration: underline;
    }

    .university-info {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    @media (max-width: 768px) {
        .team-header h1 { font-size: 1.8rem; }
        .section-title { font-size: 1.4rem; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .team-card { padding: 1.5rem; }
        .team-avatar { width: 100px; height: 100px; }
        .team-avatar i { font-size: 2.5rem; }
    }
</style>

<div class="team-page">
    <div class="team-header">
        <div class="container">
            <h1>من نحن</h1>
            <p>فريق عمل دليلي الذكي – شباب يمني يسعى لخدمة وطنه</p>
        </div>
    </div>

    <div class="container py-4">
        <!-- فريق العمل -->
        <div class="section-title">فريق العمل</div>
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-user-graduate"></i></div>
                    <h3>أصيل العامري</h3>
                    <div class="team-role">باحث ومطور رئيسي</div>
                    <p class="team-bio">مسؤول عن تطوير النظام، تصميم قاعدة البيانات، وتكامل واجهات البرمجة (APIs). لديه شغف كبير بتقنيات الويب الحديثة.</p>
                    <div class="team-social">
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="team-card">
                    <div class="team-avatar"><i class="fas fa-user-graduate"></i></div>
                    <h3>إياد الصلوي</h3>
                    <div class="team-role">باحث ومطور</div>
                    <p class="team-bio">مسؤول عن تصميم واجهات المستخدم (UI/UX)، تحسين تجربة المستخدم، وجمع البيانات الميدانية. يهتم بالتصميم والإبداع.</p>
                    <div class="team-social">
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- المشرف الأكاديمي -->
        <div class="section-title">الإشراف الأكاديمي</div>
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <div class="team-card" style="max-width: 400px; margin: 0 auto;">
                    <div class="team-avatar"><i class="fas fa-chalkboard-user"></i></div>
                    <h3>د. إسماعيل الأحمد</h3>
                    <div class="team-role">مشرف المشروع</div>
                    <p class="team-bio">أستاذ في قسم تقنية المعلومات، جامعة السعيدة. أشرف على جميع مراحل المشروع من التحليل إلى التنفيذ، وقدم الدعم العلمي والتقني للفريق.</p>
                </div>
            </div>
        </div>

        <!-- عن المشروع -->
        <div class="section-title">عن المشروع</div>
        <div class="project-card">
            <div class="project-icon"><i class="fas fa-graduation-cap"></i></div>
            <h3>مشروع تخرج بكالوريوس</h3>
            <p>تم إنجاز هذا المشروع كجزء من متطلبات نيل شهادة البكالوريوس في تخصص تقنية المعلومات<br>بكلية الهندسة وتقنية المعلومات، جامعة السعيدة.</p>
            <div class="project-year"><i class="fas fa-calendar-alt me-1"></i> 2026</div>
        </div>

        <!-- إحصائيات -->
        <div class="section-title">إنجازاتنا</div>
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
            <p><i class="fas fa-envelope text-primary me-2"></i> <a href="mailto:info@dalili.com">info@dalili.com</a></p>
            <div class="university-info">
                <p class="text-muted small mb-0"><i class="fas fa-university me-1"></i> جامعة السعيدة - كلية الهندسة وتقنية المعلومات</p>
                <p class="text-muted small mt-2"><i class="fas fa-map-marker-alt me-1"></i> اليمن - صنعاء</p>
            </div>
        </div>
    </div>
</div>
@endsection
