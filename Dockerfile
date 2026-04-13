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

# متغيرات قاعدة البيانات
ENV DB_CONNECTION=pgsql
ENV DB_HOST=dpg-d7e39b3eo5us73811kk0-a
ENV DB_PORT=5432
ENV DB_DATABASE=dlili_db
ENV DB_USERNAME=dlili_db_user
ENV DB_PASSWORD=H2pwSsLZ4bfBvvdnHO8sLyw8nvYLoqoB

# تثبيت dependencies PHP
RUN composer install --no-dev --optimize-autoloader

# ==============================================
# تجميع أصول Vite
# ==============================================
RUN npm install
RUN npm run build

# ==============================================

# إصلاح صلاحيات المجلدات
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# ربط مجلد التخزين
RUN php artisan storage:link

# إنشاء .env مؤقت لـ key:generate
RUN touch .env && echo "APP_ENV=production" >> .env

# توليد APP_KEY
RUN php artisan key:generate --force --no-interaction

# حذف .env المؤقت
RUN rm .env

# تشغيل migrations
RUN php artisan migrate --force --no-interaction

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
# إعادة توجيه جميع طلبات الأصول إلى مجلد public
RUN echo '<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_URI} !^/public/\n    RewriteRule ^(build/.*)$ /public/$1 [L,NC]\n</IfModule>' >> /var/www/html/.htaccess
CMD ["apache2-foreground"]
