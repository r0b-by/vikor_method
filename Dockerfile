# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install dependency sistem + PostgreSQL driver + Node.js 18
RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev \
    libonig-dev libxml2-dev curl git gnupg \
    ca-certificates lsb-release libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# ✅ Install Composer (copy dari image resmi)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Set direktori kerja Laravel
WORKDIR /var/www/html

# ✅ Salin semua file Laravel
COPY . .

# ✅ Pastikan file .env tersedia
RUN cp .env.example .env || true

# ✅ Install dependency PHP (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ Install dan build asset frontend (pastikan ada vite.config.js)
RUN npm install && npm run build

# ✅ Artisan discovery & cache (hapus config lama dulu)
RUN rm -f bootstrap/cache/config.php && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Jalankan migrasi dan seeder (lanjutkan walau gagal)
RUN php artisan migrate --force || echo "migrate failed (OK)" && \
    php artisan db:seed --class=DatabaseSeeder --force || echo "seed failed (OK)"

# ✅ Buat symlink ke storage/public
RUN php artisan storage:link || echo "storage link already exists (OK)"

# ✅ Set permission folder Laravel (untuk Apache)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan mod_rewrite & arahkan ke folder /public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Expose port 80
EXPOSE 80

# ✅ Jalankan Apache saat container aktif
CMD ["apache2-foreground"]
