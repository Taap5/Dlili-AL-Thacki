@extends('layouts.app')

@section('title', 'عن النظام - دليلي الذكي')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1a2366;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --border-light: rgba(47, 62, 158, 0.1);
    }

    .about-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .about-page::before {
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

    .about-page::after {
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
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    .about-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .about-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        animation: fadeInUp 0.6s ease;
    }

    .about-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
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
        position: relative;
        text-align: center;
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

    .mission-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
        height: 100%;
    }

    .mission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
        border-color: var(--primary);
    }

    .mission-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .mission-card:hover .mission-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .mission-card:hover .mission-icon i {
        color: white;
    }

    .mission-icon i {
        font-size: 2rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .mission-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }

    .mission-card p {
        color: var(--text-muted);
        line-height: 1.6;
    }

    .why-card {
        background: linear-gradient(135deg, #ffffff, #f0f4ff);
        border-radius: 28px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s;
    }

    .why-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(47, 62, 158, 0.1);
    }

    .feature-item {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
        height: 100%;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(47, 62, 158, 0.1);
        border-color: var(--primary);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-item:hover .feature-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .feature-item:hover .feature-icon i {
        color: white;
    }

    .feature-icon i {
        font-size: 1.8rem;
        color: var(--primary);
        transition: all 0.3s;
    }

    .feature-item h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .feature-item p {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .stats-section {
        background: white;
        border-radius: 28px;
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
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .tech-stack {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
    }

    .tech-item {
        background: white;
        border-radius: 40px;
        padding: 0.6rem 1.5rem;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }

    .tech-item:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .tech-item i {
        font-size: 1.2rem;
        color: var(--primary);
    }

    .tech-item span {
        font-weight: 500;
        color: var(--text-dark);
    }

    @media (max-width: 768px) {
        .about-header h1 { font-size: 1.8rem; }
        .section-title { font-size: 1.4rem; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .feature-item { padding: 1rem; }
    }
</style>

<div class="about-page">
    <div class="about-header">
        <div class="container">
            <h1>دليلي الذكي</h1>
            <p>منصة ذكية تربط المواطن بالخدمات الحكومية بسهولة ودقة</p>
        </div>
    </div>

    <div class="container py-4">
        <!-- الرؤية والرسالة والأهداف -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="mission-card">
                    <div class="mission-icon"><i class="fas fa-eye"></i></div>
                    <h3>رؤيتنا</h3>
                    <p>أن نكون المنصة الرقمية الأولى في اليمن لتسهيل الوصول إلى الخدمات الحكومية، ورفع كفاءة التواصل بين المواطن والمؤسسات الحكومية.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card">
                    <div class="mission-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>رسالتنا</h3>
                    <p>تقديم معلومات دقيقة وموثوقة عن الخدمات الحكومية، وتوفير أدوات ذكية تساعد المواطن في الوصول إليها بأقل وقت وجهد.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card">
                    <div class="mission-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>أهدافنا</h3>
                    <p>تسهيل الوصول للمعلومات الحكومية، تقليل الوقت والجهد، زيادة الشفافية، وتحسين تجربة المواطن مع الخدمات العامة.</p>
                </div>
            </div>
        </div>

        <!-- لماذا تم إنشاء المنصة -->
        <div class="why-card">
            <div class="mission-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light)); margin: 0 auto 20px;">
                <i class="fas fa-question-circle" style="color: white;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark);">لماذا دليلي الذكي؟</h3>
            <p style="max-width: 800px; margin: 0 auto; color: var(--text-muted); line-height: 1.7;">
                انطلاقاً من الحاجة الملحة لتسهيل وصول المواطن اليمني إلى الخدمات الحكومية،
                والتي غالباً ما تكون موزعة في مواقع متفرقة يصعب الوصول إليها،
                جاءت فكرة <strong>دليلي الذكي</strong> لتكون الحل الأمثل.
            </p>
            <p style="max-width: 800px; margin: 15px auto 0; color: var(--text-muted); line-height: 1.7;">
                نهدف إلى جمع كل المعلومات عن الجهات والخدمات الحكومية في مكان واحد،
                مع توفير أدوات ذكية مثل الخرائط والتقييمات والمفضلة،
                لتوفير الوقت والجهد على المواطن وتحسين تجربته مع الخدمات العامة.
            </p>
        </div>

        <!-- المميزات -->
        <div class="section-title">ما يميز منصتنا</div>
        <div class="row g-4 mb-5">
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>مواقع دقيقة</h4>
                    <p>تحديد مواقع الجهات الحكومية بدقة على الخريطة مع إرشادات الوصول التفصيلية</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h4>أوقات العمل</h4>
                    <p>عرض أوقات العمل الرسمية والتحديثات الفورية لأي تغييرات طارئة</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-star"></i></div>
                    <h4>تقييمات حقيقية</h4>
                    <p>تقييمات وتعليقات من المستخدمين لضمان شفافية المعلومات</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-heart"></i></div>
                    <h4>مفضلة ذكية</h4>
                    <p>حفظ الجهات والخدمات المفضلة للوصول السريع إليها</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-route"></i></div>
                    <h4>توجيه ذكي</h4>
                    <p>حساب المسار والمسافة والوقت للوصول إلى الخدمة المطلوبة</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h4>تصميم متجاوب</h4>
                    <p>واجهة مستخدم بسيطة تدعم اللغتين العربية والإنجليزية وتعمل على جميع الأجهزة</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-ambulance"></i></div>
                    <h4>طوارئ 24/7</h4>
                    <p>زر طوارئ عائم للوصول السريع إلى أقرب مستشفى في حالات الطوارئ</p>
                </div>
            </div>
        </div>

        <!-- إحصائيات -->
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
                    <div class="stat-label">مستخدم نشط</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ \App\Models\Review::count() }}</div>
                    <div class="stat-label">تقييم</div>
                </div>
            </div>
        </div>

        <!-- التقنيات المستخدمة -->
        <div class="section-title">التقنيات المستخدمة</div>
        <div class="tech-stack">
            <div class="tech-item"><i class="fab fa-laravel"></i><span>Laravel</span></div>
            <div class="tech-item"><i class="fab fa-php"></i><span>PHP</span></div>
            <div class="tech-item"><i class="fab fa-js"></i><span>JavaScript</span></div>
            <div class="tech-item"><i class="fab fa-bootstrap"></i><span>Bootstrap</span></div>
            <div class="tech-item"><i class="fas fa-database"></i><span>MySQL</span></div>
            <div class="tech-item"><i class="fas fa-map"></i><span>OpenStreetMap</span></div>
            <div class="tech-item"><i class="fas fa-code"></i><span>AJAX</span></div>
            <div class="tech-item"><i class="fab fa-css3-alt"></i><span>CSS3</span></div>
        </div>
    </div>
</div>
@endsection
