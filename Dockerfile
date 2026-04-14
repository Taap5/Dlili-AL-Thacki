FROM php:8.2-apache

# 1. تثبيت المتطلبات الأساسية
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

# 2. تفعيل مود Rewrite
RUN a2enmod rewrite

# 3. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. نسخ الملفات
COPY . .

# 5. تثبيت المكتبات وبناء الملفات
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 6. إصلاح الصلاحيات (تم دمج الحل لضمان إنشاء الحسابات والصور معاً)
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 7. تحسينات الأداء
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# 8. توجيه الأباتشي للمجلد العام
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# 9. الحل الذكي: إنشاء الرابط الرمزي وتشغيل السيرفر في أمر واحد عند الإقلاع
CMD php artisan storage:link --force && apache2-foreground
