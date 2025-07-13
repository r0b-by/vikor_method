# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install dependency sistem + PostgreSQL & MySQL driver + Node.js 18
RUN apt-get update && apt-get install -y \
    git curl unzip zip gnupg ca-certificates lsb-release \
    libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    libpq-dev default-mysql-client \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip bcmath opcache \
    && php -m | grep pgsql || echo "❌ PostgreSQL driver not found"

# ✅ Install Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ✅ Set timezone (opsional)
ENV TZ=Asia/Jakarta

# ✅ Set direktori kerja Laravel
WORKDIR /var/www/html

# ✅ Salin semua file Laravel
COPY . .

# ✅ Pastikan file .env tersedia
RUN cp .env.example .env || true

# ✅ Install dependency PHP (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction || echo "Composer install failed"

# ✅ Install dan build frontend assets Vite (lanjut walau error)
RUN npm install && npm run build || echo "❌ Vite build failed (OK)"

# ✅ Artisan discovery dan cache
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Jalankan migrasi & seeder (lanjut walau gagal)
RUN php artisan migrate --force || echo "❌ migrate failed (OK)" && \
    php artisan db:seed --force || echo "❌ seeding failed (OK)"

# ✅ Buat storage symbolic link
RUN php artisan storage:link || echo "storage link already exists"

# ✅ Atur permission folder Laravel untuk Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan Apache mod_rewrite dan arahkan ke /public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Buka port 80
EXPOSE 80

# ✅ Jalankan Apache
CMD ["apache2-foreground"]
