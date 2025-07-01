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

# ✅ Salin semua source code awal agar `.env` tersedia sebelum install
COPY . .

# ✅ Pastikan .env tersedia (hindari error artisan package:discover)
RUN cp .env.example .env || true

# ✅ Buat database SQLite
RUN mkdir -p database && touch database/database.sqlite

# ✅ Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ Install dan build frontend (Vite)
RUN npm install && npm run build

# ✅ Laravel cache
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Migrasi & seeder (lewati error jika DB belum siap)
RUN php artisan migrate --force || true
RUN php artisan db:seed --class=DatabaseSeeder --force || true

# ✅ Set permission untuk Laravel
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan rewrite & arahkan root ke public/
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Expose port Apache
EXPOSE 80
