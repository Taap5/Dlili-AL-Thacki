FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql pgsql zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# تثبيت dependencies
RUN composer install --optimize-autoloader --no-dev

# إنشاء مجلدات storage وتصحيح الصلاحيات
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# تعديل DocumentRoot إلى مجلد public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# ======================================================
# 🚀 الأوامر السحرية لإنهاء مشكلة 500
# ======================================================

# 1. توليد APP_KEY تلقائياً (لن يعمل إذا كان موجوداً في .env)
RUN php artisan key:generate --force --no-interaction

# 2. تخزين الإعدادات في ذاكرة التخزين المؤقتة
RUN php artisan config:cache

# 3. تشغيل migrations لإنشاء الجداول في قاعدة البيانات
RUN php artisan migrate --force --no-interaction

# ======================================================

EXPOSE 80

CMD ["apache2-foreground"]
