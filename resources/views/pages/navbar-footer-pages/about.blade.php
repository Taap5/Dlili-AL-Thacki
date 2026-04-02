@extends('layouts.app')

@section('title', 'عن النظام - دليلي الذكي')

@section('content')
    <style>
        :root {
            --primary: #2f3e9e;
            --primary-light: #5a6fc9;
            --primary-dark: #1e2a6e;
            --secondary: #ffc107;
            --bg-light: #fef9f0;
            --text-dark: #1a2c3e;
            --text-muted: #6c757d;
        }

        .about-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .about-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 150%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .about-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 16px;
            animation: fadeInUp 0.6s ease;
        }

        .about-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
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

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 50px 0 30px 0;
            position: relative;
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            border-radius: 3px;
        }

        /* بطاقات الرؤية والرسالة */
        .mission-card {
            background: white;
            border-radius: 28px;
            padding: 32px 24px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .mission-card::before {
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

        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
        }

        .mission-card:hover::before {
            transform: scaleX(1);
        }

        .mission-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .mission-card:hover .mission-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            transform: scale(1.05);
        }

        .mission-icon i {
            font-size: 36px;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .mission-card:hover .mission-icon i {
            color: white;
        }

        .mission-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .mission-card p {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* بطاقات المميزات */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin: 20px 0;
        }

        .feature-item {
            background: white;
            border-radius: 24px;
            padding: 28px 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(47, 62, 158, 0.08);
            border-color: rgba(47, 62, 158, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            transform: scale(1.05);
        }

        .feature-icon i {
            font-size: 30px;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon i {
            color: white;
        }

        .feature-item h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .feature-item p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        /* إحصائيات */
        .stats-section {
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            border-radius: 28px;
            padding: 48px 40px;
            margin: 40px 0;
            border: 1px solid rgba(47, 62, 158, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 16px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 8px;
            font-weight: 500;
        }

        /* التقنيات المستخدمة */
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
            margin: 30px 0 50px;
        }

        .tech-item {
            background: white;
            border-radius: 60px;
            padding: 10px 24px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .tech-item:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 62, 158, 0.1);
        }

        .tech-item i {
            font-size: 20px;
            color: var(--primary);
        }

        .tech-item span {
            font-weight: 500;
            color: var(--text-dark);
        }

        /* فريق العمل */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 30px 0 40px;
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
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(47, 62, 158, 0.15);
        }

        .team-card:hover::before {
            transform: scaleX(1);
        }

        .team-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .team-card:hover .team-avatar {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            transform: scale(1.05);
        }

        .team-avatar i {
            font-size: 48px;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .team-card:hover .team-avatar i {
            color: white;
        }

        .team-card h4 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .team-card p {
            color: var(--primary);
            font-weight: 500;
            font-size: 0.9rem;
            margin: 0;
        }

        /* معلومات المشروع */
        .project-info {
            text-align: center;
            margin: 40px 0 20px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 24px;
        }

        .project-info p {
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .project-info i {
            color: var(--primary);
            margin-left: 6px;
        }

        /* استجابة للهواتف */
        @media (max-width: 768px) {
            .about-header h1 {
                font-size: 2rem;
            }

            .about-header p {
                font-size: 1rem;
                padding: 0 20px;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .mission-card {
                padding: 24px 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .tech-item {
                padding: 6px 16px;
                font-size: 0.85rem;
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
        <!-- الرؤية والرسالة والأهداف -->
        <!-- الرؤية والرسالة والأهداف -->
        <div class="row g-4 mb-5">
            <div class="col-md-4" id="vision">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>رؤيتنا</h3>
                    <p>أن نكون المنصة الرقمية الأولى في اليمن لتسهيل الوصول إلى الخدمات الحكومية، ورفع كفاءة التواصل بين
                        المواطن والمؤسسات الحكومية.</p>
                </div>
            </div>
            <div class="col-md-4" id="mission">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>رسالتنا</h3>
                    <p>تقديم معلومات دقيقة وموثوقة عن الخدمات الحكومية، وتوفير أدوات ذكية تساعد المواطن في الوصول إليها بأقل
                        وقت وجهد.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>أهدافنا</h3>
                    <p>تسهيل الوصول للمعلومات الحكومية، تقليل الوقت والجهد، زيادة الشفافية، وتحسين تجربة المواطن مع الخدمات
                        العامة.</p>
                </div>
            </div>
        </div>

        <!-- لماذا تم إنشاء المنصة -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="mission-card" style="background: linear-gradient(135deg, #f8f9fa, #ffffff);">
                    <div class="mission-icon"
                        style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        <i class="fas fa-question-circle" style="color: white;"></i>
                    </div>
                    <h3>لماذا دليلي الذكي؟</h3>
                    <p style="max-width: 800px; margin: 0 auto;">
                        انطلاقاً من الحاجة الملحة لتسهيل وصول المواطن اليمني إلى الخدمات الحكومية،
                        والتي غالباً ما تكون موزعة في مواقع متفرقة يصعب الوصول إليها،
                        جاءت فكرة <strong>دليلي الذكي</strong> لتكون الحل الأمثل.
                    </p>
                    <p style="max-width: 800px; margin: 15px auto 0; color: var(--text-muted);">
                        نهدف إلى جمع كل المعلومات عن الجهات والخدمات الحكومية في مكان واحد،
                        مع توفير أدوات ذكية مثل الخرائط والتقييمات والمفضلة،
                        لتوفير الوقت والجهد على المواطن وتحسين تجربته مع الخدمات العامة.
                    </p>
                </div>
            </div>
        </div>

        <!-- ... باقي المحتوى ... -->

        <!-- فريق العمل -->
        <div class="section-title">فريق العمل</div>
        <div class="team-grid" id="team">
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4>أصيل العامري</h4>
                <p>باحث ومطور رئيسي</p>
            </div>
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4>إياد الصلوي</h4>
                <p>باحث ومطور</p>
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
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-ambulance"></i>
                </div>
                <h4>طوارئ 24/7</h4>
                <p>زر طوارئ عائم للوصول السريع إلى أقرب مستشفى في حالات الطوارئ</p>
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
            <div class="tech-item">
                <i class="fab fa-css3-alt"></i>
                <span>CSS3</span>
            </div>
        </div>

        <!-- فريق العمل -->
        <div class="section-title">فريق العمل</div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4>أصيل العامري</h4>
                <p>باحث ومطور رئيسي</p>
            </div>
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4>إياد الصلوي</h4>
                <p>باحث ومطور</p>
            </div>
        </div>

        <!-- معلومات المشروع -->
        <div class="project-info">
            <p>
                <i class="fas fa-graduation-cap"></i>
                تم إنجاز هذا المشروع كجزء من متطلبات نيل شهادة البكالوريوس في تخصص تقنية المعلومات
            </p>
            <p class="small">
                <i class="fas fa-university"></i>
                جامعة السعيدة - كلية الهندسة وتقنية المعلومات
            </p>
            <p class="small text-muted">
                <i class="fas fa-calendar-alt"></i>
                2026
            </p>
        </div>
    </div>
@endsection
