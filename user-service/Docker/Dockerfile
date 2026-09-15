FROM php:8.4-apache

WORKDIR /var/www/html
# 1. تثبيت حزم النظام المطلوبة
RUN apt-get update && apt-get install -y \
    supervisor \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. تثبيت إضافات PHP (تم إضافة sockets هنا)
RUN docker-php-ext-install pdo pdo_mysql bcmath sockets
# 3. تفعيل موديل mod_rewrite في Apache
RUN a2enmod rewrite
# 4. جلب Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# 5. نسخ ملفات المشروع وتثبيت الحزم
COPY . .

RUN composer install --no-interaction --prefer-dist
# 6. ضبط صلاحيات المجلدات الخاص بـ Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
# 7. ضبط إعدادات Apache لـ Laravel Public Directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf

RUN a2enconf laravel

EXPOSE 80
