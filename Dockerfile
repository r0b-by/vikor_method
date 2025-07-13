# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install system dependencies + PostgreSQL + Node.js 18
RUN apt-get update && apt-get install -y \
    zip unzip libzip-dev libpng-dev \
    libonig-dev libxml2-dev curl git gnupg \
    ca-certificates lsb-release libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# ✅ Install Composer (dari container resmi)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Set working directory
WORKDIR /var/www/html

# ✅ Salin semua file Laravel project
COPY . .

# ✅ Pastikan file .env tersedia
RUN cp .env.example .env || true

# ✅ Install dependency PHP (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ Build frontend dengan Vite (pastikan sudah setup vite.config.js & package.json)
RUN npm install && npm run build

# ✅ Laravel: package discovery dan cache
RUN php artisan package:discover && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Migrasi dan seeding database (abaikan error agar build tetap lanjut)
RUN php artisan migrate --force || echo "migrate failed (OK)" && \
    php artisan db:seed --class=DatabaseSeeder --force || echo "seed failed (OK)"

# ✅ Buat symlink ke storage/public
RUN php artisan storage:link || echo "storage link already exists (OK)"

# ✅ Atur permission folder Laravel
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan mod_rewrite & ubah DocumentRoot ke public/
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Expose port 80 untuk Apache
EXPOSE 80

# ✅ Start Apache saat container dijalankan
CMD ["apache2-foreground"]
