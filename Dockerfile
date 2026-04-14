FROM php:8.2-apache

# تحديث المستودعات وتثبيت المتطلبات مع إضافة خيار تجنب الأخطاء المؤقتة
RUN apt-get update && apt-get install -y --no-install-recommends \
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
    && docker-php-ext-install gd pdo pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

# إعطاء الصلاحيات لكامل المجلد لضمان عدم حدوث خطأ Permission Denied مجدداً
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# توجيه الأباتشي للمجلد العام
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# تنفيذ الرابط الرمزي عند التشغيل الفعلي لضمان ظهور الصور
CMD php artisan storage:link --force && apache2-foreground
