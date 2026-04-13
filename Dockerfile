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

# تعيين متغير بيئة مؤقت لتجنب الاتصال بقاعدة البيانات
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/tmp/dummy.sqlite

# إنشاء ملف قاعدة بيانات مؤقت
RUN touch /tmp/dummy.sqlite

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

# تشغيل أوامر Laravel مع تجاهل الأخطاء

RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan package:discover --ansi || true

EXPOSE 80

CMD ["apache2-foreground"]
