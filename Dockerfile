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
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql pgsql zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# إعداد الصلاحيات بشكل كامل قبل أي شيء آخر
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# حذف أي كاش قديم قد يسبب تعارض في المسارات
RUN php artisan config:clear || true
RUN php artisan route:clear || true

# توجيه الأباتشي للمجلد العام
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# تنفيذ الرابط الرمزي ثم تشغيل الأباتشي
CMD php artisan storage:link --force && apache2-foreground
