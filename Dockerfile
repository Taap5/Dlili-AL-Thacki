FROM php:8.2-apache

# 1. تثبيت المتطلبات الأساسية للنظام والإضافات المطلوبة للارفل
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql pgsql zip

# 2. تفعيل مود Rewrite الخاص بـ Apache لتشغيل روابط لارافل
RUN a2enmod rewrite

# 3. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. تحديد مسار العمل
WORKDIR /var/www/html

# 5. نسخ ملفات المشروع
COPY . .

# 6. تثبيت مكتبات PHP وتحسين الأداء
RUN composer install --no-dev --optimize-autoloader

# 7. بناء ملفات الفرونت إند (Vite)
RUN npm install && npm run build

# 8. إصلاح الصلاحيات (الحل الجذري لمشكلة Permission Denied)
# نقوم بإنشاء المجلدات أولاً ثم تغيير الملكية للمستخدم www-data الخاص بـ Apache
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 9. تحسينات الأداء (تخزين الإعدادات والمسارات في الكاش)
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# 10. توجيه Apache إلى مجلد public مباشرة
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# 11. الأمر النهائي: التأكد من إنشاء الرابط الرمزي وتشغيل السيرفر
# تم نقل storage:link هنا ليعمل عند إقلاع الحاوية ويصلح أي روابط مكسورة
CMD php artisan storage:link --force && apache2-foreground
