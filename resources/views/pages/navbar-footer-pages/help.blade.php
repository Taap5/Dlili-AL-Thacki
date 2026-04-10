@extends('layouts.app')

@section('title', 'المساعدة - دليلي الذكي')

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

    .help-page {
        background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .help-page::before {
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

    .help-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .help-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        animation: fadeInUp 0.6s ease;
    }

    .help-header p {
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
        font-size: 1.5rem;
        font-weight: 700;
        margin: 2rem 0 1.5rem;
        position: relative;
        padding-right: 1rem;
        color: var(--text-dark);
    }

    .section-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 30px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
    }

    .help-card {
        background: white;
        border-radius: 24px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
    }

    .help-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(47, 62, 158, 0.08);
        border-color: var(--primary);
    }

    .help-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .help-card h3 i {
        font-size: 1.5rem;
    }

    .help-card ul {
        padding-right: 1.5rem;
        margin: 1rem 0;
    }

    .help-card li {
        margin-bottom: 0.5rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .tip-box {
        background: #f0f4ff;
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
        border-right: 3px solid var(--primary);
    }

    .tip-box i {
        color: var(--primary);
        margin-left: 0.5rem;
    }

    .emergency-tip {
        background: #fff0f0;
        border-right-color: #dc3545;
    }

    .faq-item {
        border-bottom: 1px solid var(--border-light);
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-weight: 700;
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
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
        padding: 0 0 1rem 0;
        color: var(--text-muted);
        line-height: 1.6;
        display: none;
    }

    .faq-answer code {
        background: #f0f4ff;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--primary);
    }

    .contact-card {
        background: linear-gradient(135deg, #ffffff, #f0f4ff);
        border-radius: 28px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0 3rem;
        border: 1px solid var(--border-light);
    }

    .contact-card h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .contact-info {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .contact-info a {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: var(--primary);
        text-decoration: none;
        padding: 0.7rem 1.5rem;
        border-radius: 40px;
        transition: all 0.3s;
        border: 1px solid var(--border-light);
    }

    .contact-info a:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .help-header h1 { font-size: 1.8rem; }
        .section-title { font-size: 1.3rem; }
        .help-card { padding: 1.2rem; }
        .help-card h3 { font-size: 1rem; }
    }
</style>

<div class="help-page">
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
            <div class="tip-box"><i class="fas fa-lightbulb"></i> <strong>نصيحة:</strong> استخدم البحث المتقدم للحصول على نتائج أكثر دقة مع فلاتر إضافية.</div>
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
            <div class="tip-box"><i class="fas fa-info-circle"></i> <strong>ملاحظة:</strong> يمكنك إضافة تقييم واحد فقط لكل جهة. يمكنك عرض جميع تقييماتك من صفحة "تقييماتي".</div>
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
            <div class="tip-box"><i class="fas fa-lock"></i> <strong>ملاحظة:</strong> هذه الميزة متاحة فقط للمستخدمين المسجلين.</div>
        </div>

        <!-- ميزة الطوارئ -->
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
            <div class="tip-box"><i class="fas fa-lightbulb"></i> <strong>نصيحة:</strong> تأكد من تشغيل خدمة تحديد الموقع (GPS) للحصول على نتائج دقيقة. إذا رفضت مشاركة الموقع، يمكنك تحديث الصفحة والمحاولة مرة أخرى.</div>
            <div class="tip-box emergency-tip mt-2"><i class="fas fa-heartbeat"></i> <strong>معلومة مهمة:</strong> هذه الميزة مصممة خصيصاً لحالات الطوارئ لتوفير الوقت والوصول السريع إلى أقرب خدمة طبية.</div>
        </div>

        <!-- الأسئلة الشائعة -->
        <div class="section-title">الأسئلة الشائعة</div>
        <div class="help-card">
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)"><span>ما هي الخدمات المتوفرة على المنصة؟</span><i class="fas fa-chevron-down"></i></div>
                <div class="faq-answer"><p>توفر المنصة معلومات عن 4 أنواع رئيسية من الخدمات: <strong>المستشفيات الحكومية</strong>، <strong>مراكز الشرطة</strong>، <strong>مكاتب الأحوال المدنية</strong>، و<strong>مكاتب البريد الحكومية</strong>. سيتم إضافة المزيد من الخدمات مستقبلاً.</p></div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)"><span>هل التسجيل في الموقع إلزامي؟</span><i class="fas fa-chevron-down"></i></div>
                <div class="faq-answer"><p>لا، يمكنك تصفح الموقع والبحث عن الخدمات والجهات دون تسجيل. ولكن التسجيل يتيح لك مزايا إضافية مثل: إضافة التقييمات، حفظ المفضلات، واستخدام ميزة الاتجاهات على الخريطة.</p></div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)"><span>كيف يمكنني تعديل أو حذف تقييمي؟</span><i class="fas fa-chevron-down"></i></div>
                <div class="faq-answer"><p>يمكنك حذف تقييمك من خلال الذهاب إلى صفحة <strong>"تقييماتي"</strong> في القائمة الجانبية، ثم الضغط على زر الحذف. ميزة تعديل التقييم ستتم إضافتها قريباً.</p></div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)"><span>هل المعلومات الموجودة في الموقع محدثة؟</span><i class="fas fa-chevron-down"></i></div>
                <div class="faq-answer"><p>نعم، يتم تحديث المعلومات بشكل دوري من خلال فريق العمل. إذا لاحظت أي معلومات غير دقيقة، يمكنك التواصل معنا للإبلاغ عنها.</p></div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)"><span>كيف يمكنني الإبلاغ عن مشكلة أو اقتراح؟</span><i class="fas fa-chevron-down"></i></div>
                <div class="faq-answer"><p>يمكنك التواصل معنا عبر البريد الإلكتروني <code>info@dalili.com</code> أو من خلال وسائل التواصل الاجتماعي الموجودة أدناه.</p></div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const firstFaq = document.querySelector('.faq-question');
        if (firstFaq) {
            firstFaq.nextElementSibling.style.display = 'block';
            firstFaq.querySelector('i').classList.remove('fa-chevron-down');
            firstFaq.querySelector('i').classList.add('fa-chevron-up');
        }
    });
</script>
@endsection
