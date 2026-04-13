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

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# تثبيت dependencies
RUN composer install --no-dev --optimize-autoloader

# ==============================================
# إصلاح كامل للصلاحيات (بأقصى صلاحية)
# ==============================================
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# تجهيز قاعدة البيانات المؤقتة لـ key:generate
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# إنشاء ملف .env مؤقت لـ key:generate
RUN touch .env

# توليد APP_KEY (سيكتبه في .env المؤقت)
RUN php artisan key:generate --force --no-interaction

# تنظيف الملفات المؤقتة (لن نحتاجها بعد الآن)
RUN rm .env database/database.sqlite

# تشغيل migrations على PostgreSQL
RUN php artisan migrate --force --no-interaction

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
