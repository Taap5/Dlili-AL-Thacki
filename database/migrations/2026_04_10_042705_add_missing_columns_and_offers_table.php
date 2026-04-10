<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // 1. إضافة أعمدة جديدة لجدول governments
        // =====================================================
        Schema::table('governments', function (Blueprint $table) {
            // بيانات الاتصال الإضافية
            if (!Schema::hasColumn('governments', 'email')) {
                $table->string('email')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('governments', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('email');
            }

            // وصف الموقع التفصيلي
            if (!Schema::hasColumn('governments', 'location_description')) {
                $table->text('location_description')->nullable()->after('location_long');
            }

            // روابط التواصل الاجتماعي
            if (!Schema::hasColumn('governments', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('work_hours');
            }
            if (!Schema::hasColumn('governments', 'telegram_url')) {
                $table->string('telegram_url')->nullable()->after('facebook_url');
            }

            // وصف قصير للبطاقة الرئيسية
            if (!Schema::hasColumn('governments', 'short_description')) {
                $table->string('short_description', 255)->nullable()->after('description');
            }

            // كلمات مفتاحية لتحسين البحث
            if (!Schema::hasColumn('governments', 'keywords')) {
                $table->text('keywords')->nullable()->after('short_description');
            }

            // حالة التفعيل
            if (!Schema::hasColumn('governments', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('keywords');
            }
        });

        // =====================================================
        // 2. إضافة أعمدة جديدة لجدول government_offer_service (تفاصيل الخدمة لكل جهة)
        // =====================================================
        Schema::table('government_offer_service', function (Blueprint $table) {
            // مدة الإنجاز (مثال: 3 أيام عمل، أسبوع، نفس اليوم)
            if (!Schema::hasColumn('government_offer_service', 'processing_time')) {
                $table->string('processing_time')->nullable()->after('price');
            }

            // موقع التقديم داخل الجهة (مثال: شباك 5، الدور الثاني، قسم الطوارئ)
            if (!Schema::hasColumn('government_offer_service', 'office_location')) {
                $table->string('office_location')->nullable()->after('processing_time');
            }

            // الأوراق المطلوبة (نص طويل مع تنسيق)
            if (!Schema::hasColumn('government_offer_service', 'required_documents')) {
                $table->text('required_documents')->nullable()->after('office_location');
            }

            // الإجراءات خطوة بخطوة
            if (!Schema::hasColumn('government_offer_service', 'steps')) {
                $table->text('steps')->nullable()->after('required_documents');
            }

            // الشروط (مثال: العمر 16 سنة فأكثر)
            if (!Schema::hasColumn('government_offer_service', 'conditions')) {
                $table->text('conditions')->nullable()->after('steps');
            }

            // ملاحظات إضافية
            if (!Schema::hasColumn('government_offer_service', 'notes')) {
                $table->text('notes')->nullable()->after('conditions');
            }

            // هل يتطلب حجز مسبق؟
            if (!Schema::hasColumn('government_offer_service', 'requires_appointment')) {
                $table->boolean('requires_appointment')->default(false)->after('notes');
            }

            // رقم الهاتف المخصص للحجز (إذا كان يتطلب حجزاً)
            if (!Schema::hasColumn('government_offer_service', 'appointment_phone')) {
                $table->string('appointment_phone')->nullable()->after('requires_appointment');
            }

            // أعمدة خاصة بالمستشفيات (اختيارية)
            if (!Schema::hasColumn('government_offer_service', 'doctor_specialist')) {
                $table->string('doctor_specialist')->nullable()->after('appointment_phone');
            }
            if (!Schema::hasColumn('government_offer_service', 'hospital_stay_duration')) {
                $table->string('hospital_stay_duration')->nullable()->after('doctor_specialist');
            }
            if (!Schema::hasColumn('government_offer_service', 'emergency_notes')) {
                $table->text('emergency_notes')->nullable()->after('hospital_stay_duration');
            }

            // حقل JSON للمرونة المستقبلية (لأي بيانات إضافية)
            if (!Schema::hasColumn('government_offer_service', 'extra_data')) {
                $table->json('extra_data')->nullable()->after('emergency_notes');
            }
        });

        // =====================================================
        // 3. إضافة عمود icon_image لجدول offer_services
        // =====================================================
        Schema::table('offer_services', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_services', 'icon_image')) {
                $table->string('icon_image')->nullable()->after('images');
            }
        });

        // =====================================================
        // 4. إنشاء جدول government_offers (العروض الخاصة)
        // =====================================================
        if (!Schema::hasTable('government_offers')) {
            Schema::create('government_offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('government_id')->constrained('governments')->onDelete('cascade');

                // معلومات العرض
                $table->string('title'); // عنوان العرض (مثال: أدوية مجانية لمرضى السكر)
                $table->text('description')->nullable(); // وصف تفصيلي

                // نوع العرض
                $table->enum('offer_type', [
                    'discount',           // تخفيض
                    'free_service',       // خدمة مجانية
                    'special_feature',    // ميزة خاصة
                    'donation',           // تبرعات/مساعدة
                    'other'               // أخرى
                ])->default('other');

                // الفئة المستهدفة (مثال: مرضى السكر، كبار السن، ذوي الاحتياجات)
                $table->string('target_audience')->nullable();

                // فترة العرض
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_permanent')->default(false); // عرض مستمر؟

                // الشروط والأحكام
                $table->text('terms')->nullable();

                // رقم للاستفسار عن هذا العرض
                $table->string('contact_number')->nullable();

                // أيقونة العرض
                $table->string('icon')->nullable();

                // حالة التفعيل
                $table->boolean('is_active')->default(true);

                $table->timestamps();

                // فهارس للبحث السريع
                $table->index('offer_type');
                $table->index('is_active');
                $table->index('is_permanent');
            });
        }
    }

    public function down(): void
    {
        // حذف الأعمدة المضافة من جدول governments
        Schema::table('governments', function (Blueprint $table) {
            $columns = ['email', 'whatsapp_number', 'location_description', 'facebook_url',
                       'telegram_url', 'short_description', 'keywords', 'is_active'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('governments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // حذف الأعمدة المضافة من جدول government_offer_service
        Schema::table('government_offer_service', function (Blueprint $table) {
            $columns = ['processing_time', 'office_location', 'required_documents', 'steps',
                       'conditions', 'notes', 'requires_appointment', 'appointment_phone',
                       'doctor_specialist', 'hospital_stay_duration', 'emergency_notes', 'extra_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('government_offer_service', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // حذف عمود icon_image من جدول offer_services
        Schema::table('offer_services', function (Blueprint $table) {
            if (Schema::hasColumn('offer_services', 'icon_image')) {
                $table->dropColumn('icon_image');
            }
        });

        // حذف جدول government_offers
        Schema::dropIfExists('government_offers');
    }
};
