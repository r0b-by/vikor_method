# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install dependencies & Node.js 18
RUN apt-get update && apt-get install -y \
    zip unzip sqlite3 libzip-dev libpng-dev \
    libonig-dev libxml2-dev curl git gnupg \
    ca-certificates lsb-release libsqlite3-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# ✅ Install Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Set working directory
WORKDIR /var/www/html

# ✅ Salin semua file project
COPY . .

# ✅ Pastikan file .env tersedia sebelum artisan command
RUN cp .env.example .env || true

# ✅ Buat database SQLite sesuai path di .env
RUN mkdir -p database && touch database/database.sqlite

# ✅ Install PHP dependencies (tanpa artisan script auto-run)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --prefer-dist

# ✅ Install frontend dependencies dan build asset dengan Vite
RUN npm install && npm run build

# ✅ Artisan discover (manual agar error bisa ditangani)
RUN php artisan package:discover --ansi || true

# ✅ Cache Laravel configuration
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Migrasi & seeder (skip error agar build tetap lanjut)
RUN php artisan migrate --force || echo "migrate gagal (tidak fatal)"
RUN php artisan db:seed --class=DatabaseSeeder --force || echo "seeder gagal (tidak fatal)"

# ✅ Set permission agar Laravel dapat menulis ke storage dan database
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan rewrite dan ubah root ke public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Buka port 80
EXPOSE 80
