@extends('layouts.app')

@section('title', 'عن النظام - دليلي الذكي')

@section('content')
<style>
    .about-header {
        background: linear-gradient(135deg, #2f3e9e 0%, #5a6fc9 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }

    .about-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .about-header p {
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

    .mission-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.3s;
    }

    .mission-card:hover {
        transform: translateY(-5px);
    }

    .mission-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .mission-icon i {
        font-size: 30px;
        color: white;
    }

    .mission-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .mission-card p {
        color: #666;
        line-height: 1.6;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .feature-item {
        background: white;
        border-radius: 16px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .feature-item:hover {
        box-shadow: 0 8px 25px rgba(47,62,158,0.1);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: #f0f2ff;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .feature-icon i {
        font-size: 28px;
        color: #2f3e9e;
    }

    .feature-item h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .feature-item p {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .tech-stack {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .tech-item {
        background: white;
        border-radius: 40px;
        padding: 8px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tech-item i {
        font-size: 20px;
        color: #2f3e9e;
    }

    .tech-item span {
        font-weight: 500;
        color: #333;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 20px;
    }

    .team-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .team-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .team-avatar i {
        font-size: 50px;
        color: white;
    }

    .team-card h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .team-card p {
        color: #666;
        font-size: 0.9rem;
    }

    .stats-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        border-radius: 24px;
        padding: 40px;
        margin-top: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 30px;
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2f3e9e;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .about-header h1 {
            font-size: 1.8rem;
        }
        .section-title {
            font-size: 1.5rem;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="about-header">
    <div class="container">
        <h1>دليلي الذكي</h1>
        <p>منصة ذكية تربط المواطن بالخدمات الحكومية بسهولة ودقة</p>
    </div>
</div>

<div class="container py-4">
    <!-- الرؤية والرسالة والأهداف -->
    <div class="row g-4 mb-5">
        <div class="col-md-4" id="vision">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>رؤيتنا</h3>
                <p>أن نكون المنصة الرقمية الأولى في اليمن لتسهيل الوصول إلى الخدمات الحكومية، ورفع كفاءة التواصل بين المواطن والمؤسسات الحكومية.</p>
            </div>
        </div>
        <div class="col-md-4" id="mission">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>رسالتنا</h3>
                <p>تقديم معلومات دقيقة وموثوقة عن الخدمات الحكومية، وتوفير أدوات ذكية تساعد المواطن في الوصول إليها بأقل وقت وجهد.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>أهدافنا</h3>
                <p>تسهيل الوصول للمعلومات الحكومية، تقليل الوقت والجهد، زيادة الشفافية، وتحسين تجربة المواطن مع الخدمات العامة.</p>
            </div>
        </div>
    </div>

    <!-- المميزات -->
    <div class="section-title">ما يميز منصتنا</div>
    <div class="features-grid mb-5">
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4>مواقع دقيقة</h4>
            <p>تحديد مواقع الجهات الحكومية بدقة على الخريطة مع إرشادات الوصول التفصيلية</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4>أوقات العمل</h4>
            <p>عرض أوقات العمل الرسمية والتحديثات الفورية لأي تغييرات طارئة</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-star"></i>
            </div>
            <h4>تقييمات حقيقية</h4>
            <p>تقييمات وتعليقات من المستخدمين لضمان شفافية المعلومات</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h4>مفضلة ذكية</h4>
            <p>حفظ الجهات والخدمات المفضلة للوصول السريع إليها</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-route"></i>
            </div>
            <h4>توجيه ذكي</h4>
            <p>حساب المسار والمسافة والوقت للوصول إلى الخدمة المطلوبة</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h4>تصميم متجاوب</h4>
            <p>واجهة مستخدم بسيطة تدعم اللغتين العربية والإنجليزية وتعمل على جميع الأجهزة</p>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="stats-section mb-5">
        <div class="stats-grid">
            <div>
                <div class="stat-number">{{ \App\Models\Government::count() }}</div>
                <div class="stat-label">جهة حكومية</div>
            </div>
            <div>
                <div class="stat-number">{{ \App\Models\OfferService::count() }}</div>
                <div class="stat-label">خدمة متوفرة</div>
            </div>
            <div>
                <div class="stat-number">{{ \App\Models\User::count() }}</div>
                <div class="stat-label">مستخدم نشط</div>
            </div>
            <div>
                <div class="stat-number">{{ \App\Models\Review::count() }}</div>
                <div class="stat-label">تقييم</div>
            </div>
        </div>
    </div>

    <!-- التقنيات المستخدمة -->
    <div class="section-title">التقنيات المستخدمة</div>
    <div class="tech-stack mb-5">
        <div class="tech-item">
            <i class="fab fa-laravel"></i>
            <span>Laravel</span>
        </div>
        <div class="tech-item">
            <i class="fab fa-php"></i>
            <span>PHP</span>
        </div>
        <div class="tech-item">
            <i class="fab fa-js"></i>
            <span>JavaScript</span>
        </div>
        <div class="tech-item">
            <i class="fab fa-bootstrap"></i>
            <span>Bootstrap</span>
        </div>
        <div class="tech-item">
            <i class="fas fa-database"></i>
            <span>MySQL</span>
        </div>
        <div class="tech-item">
            <i class="fas fa-map"></i>
            <span>OpenStreetMap</span>
        </div>
        <div class="tech-item">
            <i class="fas fa-code"></i>
            <span>AJAX</span>
        </div>
    </div>

    <!-- فريق العمل -->
    <div class="section-title">فريق العمل</div>
    <div class="team-grid mb-5" id="team">
        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h4>أصيل العامري</h4>
            <p>باحث ومطور</p>
        </div>
        <div class="team-card">
            <div class="team-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h4>إياد الصلوي</h4>
            <p>باحث ومطور</p>
        </div>
    </div>

    <div class="text-center mb-5">
        <p class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i>
            تم إنجاز هذا المشروع كجزء من متطلبات نيل شهادة البكالوريوس في تخصص تقنية المعلومات
        </p>
        <p class="text-muted small">
            <i class="fas fa-university me-1"></i>
            جامعة السعيدة - كلية الهندسة وتقنية المعلومات
        </p>
    </div>
</div>
@endsection
