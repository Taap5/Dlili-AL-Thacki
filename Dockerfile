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

# تجاهل قاعدة البيانات أثناء تثبيت composer
RUN composer install --optimize-autoloader --no-dev --ignore-platform-req=ext-pdo_sqlite

# إنشاء مجلد storage وتصحيح الصلاحيات
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# تشغيل أوامر Laravel بعد تعيين متغيرات البيئة
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 80

CMD ["apache2-foreground"]
