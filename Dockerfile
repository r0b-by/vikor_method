# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install system dependencies & Node.js 18
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

# ✅ Salin semua file project ke dalam container
COPY . .

# ✅ Siapkan .env dan file database SQLite
RUN cp .env.example .env || true && \
    mkdir -p database && touch database/database.sqlite

# ✅ Install dependencies tanpa require-dev
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ Build assets frontend menggunakan Vite
RUN npm install && npm run build

# ✅ Laravel package discovery dan cache konfigurasi
RUN php artisan package:discover --ansi && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Jalankan migrate dan seed (jangan hentikan build kalau gagal)
RUN php artisan migrate --force || echo "migrate failed (OK)" && \
    php artisan db:seed --class=DatabaseSeeder --force || echo "seed failed (OK)"

# ✅ Set permission agar Laravel bisa menulis ke folder penting
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan mod_rewrite dan arahkan DocumentRoot ke /public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Expose port Apache
EXPOSE 80
