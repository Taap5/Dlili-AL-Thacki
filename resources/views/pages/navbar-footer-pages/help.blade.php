@extends('layouts.app')

@section('title', 'المساعدة - دليلي الذكي')

@section('content')
<style>
    .help-header {
        background: linear-gradient(135deg, #2f3e9e 0%, #5a6fc9 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }

    .help-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .help-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 40px 0 25px 0;
        position: relative;
        padding-bottom: 12px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #2f3e9e, #5a6fc9);
        border-radius: 3px;
    }

    .help-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .help-card:hover {
        box-shadow: 0 8px 25px rgba(47,62,158,0.1);
    }

    .help-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #2f3e9e;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .help-card h3 i {
        font-size: 1.5rem;
    }

    .help-card p {
        color: #555;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .help-card ul {
        padding-right: 20px;
        margin-top: 10px;
    }

    .help-card li {
        margin-bottom: 8px;
        color: #555;
    }

    .help-card code {
        background: #f5f5f5;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #2f3e9e;
    }

    .faq-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-weight: 700;
        font-size: 1.1rem;
        color: #1a2c3e;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question:hover {
        color: #2f3e9e;
    }

    .faq-answer {
        padding-top: 10px;
        color: #666;
        line-height: 1.6;
    }

    .contact-card {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        margin-top: 30px;
    }

    .contact-card h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .contact-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .contact-info a {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #2f3e9e;
        text-decoration: none;
        padding: 8px 16px;
        background: white;
        border-radius: 40px;
        transition: all 0.3s;
    }

    .contact-info a:hover {
        background: #2f3e9e;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .help-header h1 {
            font-size: 1.8rem;
        }
        .section-title {
            font-size: 1.4rem;
        }
        .contact-info {
            gap: 15px;
        }
        .contact-info a {
            font-size: 12px;
            padding: 6px 12px;
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
            <li><strong>شريط البحث الرئيسي:</strong> اكتب اسم الخدمة أو الجهة في مربع البحث، ثم اختر التصنيف المناسب من القائمة المنسدلة.</li>
            <li><strong>البطاقات الرئيسية:</strong> اضغط على أي بطاقة من بطاقات التصنيفات (مستشفيات، أقسام الشرطة، إلخ) لاستعراض جميع الجهات في هذا التصنيف.</li>
            <li><strong>الاقتراحات اللحظية:</strong> أثناء الكتابة في شريط البحث، ستظهر لك اقتراحات فورية للخدمات والجهات المتاحة.</li>
        </ul>
        <p class="text-muted small mt-2"><i class="fas fa-lightbulb"></i> نصيحة: استخدم التصنيفات لتضييق نطاق البحث والحصول على نتائج أكثر دقة.</p>
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
        <p class="text-muted small mt-2"><i class="fas fa-info-circle"></i> ملاحظة: يمكنك إضافة تقييم واحد فقط لكل جهة. إذا أردت تعديل تقييمك، يمكنك حذفه وإضافته مرة أخرى.</p>
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
            <li>يمكنك مشاهدة جميع مفضلاتك من خلال <strong>القائمة الجانبية</strong> ثم اختيار <strong>"المفضلة"</strong>.</li>
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
        <p class="text-muted small mt-2"><i class="fas fa-lock"></i> ملاحظة: هذه الميزة متاحة فقط للمستخدمين المسجلين.</p>
    </div>

    <!-- الأسئلة الشائعة -->
    <div class="section-title">الأسئلة الشائعة</div>
    <div class="help-card">
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>ما هي الخدمات المتوفرة على المنصة؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer" style="display: none;">
                <p>توفر المنصة معلومات عن 4 أنواع رئيسية من الخدمات: المستشفيات الحكومية، مراكز الشرطة، مكاتب الأحوال المدنية، ومكاتب البريد الحكومية. سيتم إضافة المزيد من الخدمات مستقبلاً.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>هل التسجيل في الموقع إلزامي؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer" style="display: none;">
                <p>لا، يمكنك تصفح الموقع والبحث عن الخدمات والجهات دون تسجيل. ولكن التسجيل يتيح لك مزايا إضافية مثل: إضافة التقييمات، حفظ المفضلات، واستخدام ميزة الاتجاهات على الخريطة.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>كيف يمكنني تعديل أو حذف تقييمي؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer" style="display: none;">
                <p>حالياً، يمكنك حذف تقييمك من خلال الذهاب إلى صفحة <strong>"تقييماتي"</strong> في القائمة الجانبية، ثم الضغط على زر الحذف. ميزة تعديل التقييم ستتم إضافتها قريباً.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>هل المعلومات الموجودة في الموقع محدثة؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer" style="display: none;">
                <p>نعم، يتم تحديث المعلومات بشكل دوري من خلال فريق العمل. إذا لاحظت أي معلومات غير دقيقة، يمكنك التواصل معنا للإبلاغ عنها.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <span>كيف يمكنني الإبلاغ عن مشكلة أو اقتراح؟</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer" style="display: none;">
                <p>يمكنك التواصل معنا عبر البريد الإلكتروني <code>info@dalili.com</code> أو من خلال وسائل التواصل الاجتماعي الموجودة في الفوتر.</p>
            </div>
        </div>
    </div>

    <!-- طرق التواصل -->
    <div class="contact-card">
        <h4><i class="fas fa-headset"></i> تحتاج مساعدة إضافية؟</h4>
        <p>فريق الدعم الفني جاهز لمساعدتك</p>
        <div class="contact-info">
            <a href="mailto:info@dalili.com"><i class="fas fa-envelope"></i> info@dalili.com</a>
            <a href="tel:+9671234567"><i class="fas fa-phone"></i> +967 1 234 567</a>
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
</script>
@endsection
