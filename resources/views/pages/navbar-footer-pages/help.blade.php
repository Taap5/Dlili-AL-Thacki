@extends('layouts.app')

@section('title', 'المساعدة - دليلي الذكي')

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

    .help-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .help-header::before {
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

    .help-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 16px;
        animation: fadeInUp 0.6s ease;
    }

    .help-header p {
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
        padding-right: 20px;
        color: var(--text-dark);
    }

    .section-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
    }

    .help-card {
        background: white;
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .help-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(47, 62, 158, 0.08);
        border-color: rgba(47, 62, 158, 0.15);
    }

    .help-card h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .help-card h3 i {
        font-size: 1.8rem;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .help-card p {
        color: var(--text-muted);
        line-height: 1.7;
        margin-bottom: 16px;
        font-size: 1rem;
    }

    .help-card ul {
        padding-right: 20px;
        margin: 16px 0;
    }

    .help-card li {
        margin-bottom: 10px;
        color: var(--text-muted);
        line-height: 1.6;
        position: relative;
        padding-right: 20px;
    }

    .help-card li::before {
        content: '•';
        position: absolute;
        right: 0;
        color: var(--primary);
        font-weight: bold;
        font-size: 1.2rem;
    }

    .help-card strong {
        color: var(--text-dark);
    }

    .tip-box {
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        border-right: 4px solid var(--primary);
        border-radius: 16px;
        padding: 16px 20px;
        margin-top: 16px;
    }

    .tip-box i {
        color: var(--primary);
        margin-left: 8px;
    }

    /* الأسئلة الشائعة */
    .faq-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 0;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0;
        transition: all 0.2s;
    }

    .faq-question:hover {
        color: var(--primary);
    }

    .faq-question i {
        color: var(--primary);
        transition: transform 0.2s;
    }

    .faq-answer {
        padding: 0 0 20px 0;
        color: var(--text-muted);
        line-height: 1.6;
        display: none;
    }

    .faq-answer code {
        background: #f0f4ff;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--primary);
    }

    /* بطاقة التواصل */
    .contact-card {
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        border-radius: 28px;
        padding: 40px;
        text-align: center;
        margin: 40px 0 60px;
        border: 1px solid rgba(47, 62, 158, 0.1);
    }

    .contact-card h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-dark);
    }

    .contact-card p {
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    .contact-info {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .contact-info a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: white;
        color: var(--primary);
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 60px;
        transition: all 0.3s ease;
        border: 1px solid rgba(47, 62, 158, 0.2);
        font-weight: 500;
    }

    .contact-info a:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(47, 62, 158, 0.2);
    }

    /* استجابة للهواتف */
    @media (max-width: 768px) {
        .help-header h1 {
            font-size: 2rem;
        }
        .help-header p {
            font-size: 1rem;
            padding: 0 20px;
        }
        .section-title {
            font-size: 1.4rem;
        }
        .help-card h3 {
            font-size: 1.2rem;
        }
        .help-card h3 i {
            width: 40px;
            height: 40px;
            font-size: 1.4rem;
        }
        .contact-info {
            gap: 12px;
        }
        .contact-info a {
            padding: 8px 16px;
            font-size: 0.85rem;
        }
        .contact-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="help-header">
    <div class="container">
        <h1>مركز المساعدة</h1>
        <p>كل ما تحتاج معرفته للاستفادة من خدمات دليلي الذكي</p>
    </div>
</div>

<div class="container py-4">
    <!-- كيفية البحث -->
    <div class="section-title">كيفية البحث عن خدمة</div>
    <div class="help-card">
        <h3><i class="fas fa-search"></i> البحث عن خدمة أو جهة</h3>
        <p>يمكنك البحث عن الخدمات والجهات الحكومية بسهولة من خلال:</p>
        <ul>
            <li><strong>شريط البحث الرئيسي:</strong> اكتب اسم الخدمة أو الجهة في مربع البحث، ثم اضغط زر البحث.</li>
            <li><strong>البطاقات الرئيسية:</strong> اضغط على أي بطاقة من بطاقات التصنيفات (مستشفيات، أقسام الشرطة، إلخ) لاستعراض جميع الجهات في هذا التصنيف.</li>
            <li><strong>الاقتراحات اللحظية:</strong> أثناء الكتابة في شريط البحث، ستظهر لك اقتراحات فورية للخدمات والجهات المتاحة.</li>
        </ul>
        <div class="tip-box">
            <i class="fas fa-lightbulb"></i>
            <strong>نصيحة:</strong> استخدم البحث المتقدم للحصول على نتائج أكثر دقة مع فلاتر إضافية.
        </div>
    </div>

    <!-- كيفية إضافة تقييم -->
    <div class="section-title">كيفية إضافة تقييم</div>
    <div class="help-card">
        <h3><i class="fas fa-star"></i> تقييم الجهات الحكومية</h3>
        <p>لتتمكن من إضافة تقييم، يجب أن تكون مسجلاً في الموقع:</p>
        <ul>
            <li>سجل دخولك إلى حسابك.</li>
            <li>انتقل إلى صفحة الجهة التي تريد تقييمها.</li>
            <li>افتح قسم <strong>"التقييمات والمراجعات"</strong>.</li>
            <li>اختر عدد النجوم (من 1 إلى 5) وأضف تعليقك.</li>
            <li>اضغط <strong>"إرسال التقييم"</strong>.</li>
        </ul>
        <div class="tip-box">
            <i class="fas fa-info-circle"></i>
            <strong>ملاحظة:</strong> يمكنك إضافة تقييم واحد فقط لكل جهة. يمكنك عرض جميع تقييماتك من صفحة "تقييماتي".
        </div>
    </div>

    <!-- كيفية إضافة إلى المفضلة -->
    <div class="section-title">كيفية إضافة إلى المفضلة</div>
    <div class="help-card">
        <h3><i class="fas fa-heart"></i> حفظ الجهات والخدمات المفضلة</h3>
        <p>يمكنك حفظ الجهات والخدمات التي تهمك للوصول إليها بسرعة:</p>
        <ul>
            <li>سجل دخولك إلى حسابك.</li>
            <li>في صفحة الجهة أو الخدمة، اضغط على زر <strong>"أضف إلى المفضلة"</strong> (زر القلب).</li>
            <li>ستتحول الأيقونة إلى لون أحمر لتأكيد الإضافة.</li>
            <li>يمكنك مشاهدة جميع مفضلاتك من خلال القائمة الجانبية ثم اختيار <strong>"المفضلة"</strong>.</li>
        </ul>
    </div>

    <!-- كيفية استخدام الخريطة -->
    <div class="section-title">كيفية استخدام الخريطة</div>
    <div class="help-card">
        <h3><i class="fas fa-map-marked-alt"></i> الخريطة والاتجاهات</h3>
        <p>تساعدك الخريطة في الوصول إلى الجهات الحكومية بسهولة:</p>
        <ul>
            <li>في صفحة الجهة، افتح قسم <strong>"الموقع الجغرافي والخريطة"</strong>.</li>
            <li>ستظهر لك الخريطة مع موقع الجهة المحدد.</li>
            <li>اضغط على <strong>"استخدم موقعي الحالي"</strong> لتحديد موقعك.</li>
            <li>اختر وسيلة النقل (سيارة أو مشي).</li>
            <li>ستظهر لك المسافة والوقت المقدر للوصول.</li>
        </ul>
        <div class="tip-box">
            <i class="fas fa-lock"></i>
            <strong>ملاحظة:</strong> هذه الميزة متاحة فقط للمستخدمين المسجلين.
        </div>
    </div>
<!-- ميزة الطوارئ (أقرب مستشفى) -->
<div class="section-title">ميزة الطوارئ - أقرب مستشفى</div>
<div class="help-card">
    <h3><i class="fas fa-ambulance"></i> زر الطوارئ (أقرب مستشفى)</h3>
    <p>في حالات الطوارئ، يمكنك استخدام زر الطوارئ العائم للعثور على أقرب مستشفى بسرعة:</p>
    <ul>
        <li><strong>موقع الزر:</strong> يظهر زر أحمر عائم في أسفل يسار الشاشة في الصفحة الرئيسية.</li>
        <li><strong>كيفية الاستخدام:</strong> اضغط على الزر، واسمح للمتصفح بالوصول إلى موقعك الحالي.</li>
        <li><strong>النتائج:</strong> ستعرض لك قائمة بأقرب 5 مستشفيات مرتبة حسب المسافة.</li>
        <li><strong>المعلومات المعروضة:</strong> اسم المستشفى، المسافة التقريبية، الزمن المقدر للوصول، ورقم الهاتف إن وجد.</li>
        <li><strong>عرض الاتجاهات:</strong> اضغط على "عرض الاتجاهات" للانتقال إلى صفحة المستشفى مع إمكانية رسم المسار.</li>
    </ul>
    <div class="tip-box">
        <i class="fas fa-lightbulb"></i>
        <strong>نصيحة:</strong> تأكد من تشغيل خدمة تحديد الموقع (GPS) للحصول على نتائج دقيقة. إذا رفضت مشاركة الموقع، يمكنك تحديث الصفحة والمحاولة مرة أخرى.
    </div>
    <div class="tip-box mt-2" style="background: #fff0f0; border-right-color: #dc3545;">
        <i class="fas fa-heartbeat"></i>
        <strong>معلومة مهمة:</strong> هذه الميزة مصممة خصيصاً لحالات الطوارئ لتوفير الوقت والوصول السريع إلى أقرب خدمة طبية.
    </div>
</div>
    <!-- الأسئلة الشائعة -->
    <div class="section-title">الأسئلة الشائعة</div>
    <div class="help-card">
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>ما هي الخدمات المتوفرة على المنصة؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>توفر المنصة معلومات عن 4 أنواع رئيسية من الخدمات: <strong>المستشفيات الحكومية</strong>، <strong>مراكز الشرطة</strong>، <strong>مكاتب الأحوال المدنية</strong>، و<strong>مكاتب البريد الحكومية</strong>. سيتم إضافة المزيد من الخدمات مستقبلاً.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>هل التسجيل في الموقع إلزامي؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>لا، يمكنك تصفح الموقع والبحث عن الخدمات والجهات دون تسجيل. ولكن التسجيل يتيح لك مزايا إضافية مثل: إضافة التقييمات، حفظ المفضلات، واستخدام ميزة الاتجاهات على الخريطة.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>كيف يمكنني تعديل أو حذف تقييمي؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>يمكنك حذف تقييمك من خلال الذهاب إلى صفحة <strong>"تقييماتي"</strong> في القائمة الجانبية، ثم الضغط على زر الحذف. ميزة تعديل التقييم ستتم إضافتها قريباً.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>هل المعلومات الموجودة في الموقع محدثة؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>نعم، يتم تحديث المعلومات بشكل دوري من خلال فريق العمل. إذا لاحظت أي معلومات غير دقيقة، يمكنك التواصل معنا للإبلاغ عنها.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>كيف يمكنني الإبلاغ عن مشكلة أو اقتراح؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>يمكنك التواصل معنا عبر البريد الإلكتروني <code>info@dalili.com</code> أو من خلال وسائل التواصل الاجتماعي الموجودة أدناه.</p>
            </div>
        </div>
    </div>

    <!-- طرق التواصل -->
    <div class="contact-card">
        <h4><i class="fas fa-headset"></i> تحتاج مساعدة إضافية؟</h4>
        <p>فريق الدعم الفني جاهز لمساعدتك على مدار الساعة</p>
        <div class="contact-info">
            <a href="mailto:info@dalili.com"><i class="fas fa-envelope"></i> info@dalili.com</a>
            <a href="#"><i class="fab fa-facebook-messenger"></i> الماسنجر</a>
            <a href="#"><i class="fab fa-whatsapp"></i> واتساب</a>
        </div>
    </div>
</div>

<script>
    function toggleFAQ(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');

        if (answer.style.display === 'none' || answer.style.display === '') {
            answer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            answer.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // فتح أول سؤال بشكل افتراضي
    document.addEventListener('DOMContentLoaded', function() {
        const firstFaq = document.querySelector('.faq-question');
        if (firstFaq) {
            const answer = firstFaq.nextElementSibling;
            const icon = firstFaq.querySelector('i');
            answer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    });
</script>
@endsection
