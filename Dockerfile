FROM php:8.2-apache

# تثبيت المتطلبات
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

# Apache rewrite
RUN a2enmod rewrite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# ==============================================
# إنشاء ملف .env مؤقت (لحل مشكلة key:generate)
# ==============================================
RUN touch .env && chmod 666 .env

# إنشاء قاعدة بيانات SQLite مؤقتة
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# تثبيت dependencies
RUN composer install --no-dev --optimize-autoloader

# إصلاح صلاحيات المجلدات
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# توليد APP_KEY (الآن سيعمل لأن .env و SQLite موجودان)
RUN php artisan key:generate --force --no-interaction

# حذف الملفات المؤقتة
RUN rm .env database/database.sqlite

# تشغيل migrations (لإنشاء جميع الجداول في PostgreSQL)
RUN php artisan migrate --force --no-interaction || true

# Apache root إلى public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
