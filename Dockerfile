# Gunakan image PHP + Apache
FROM php:8.2-apache

# ✅ Install system dependencies (PHP ext + SQLite + Node)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev curl git \
    gnupg ca-certificates lsb-release \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_sqlite zip

# ✅ Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Set working directory
WORKDIR /var/www/html

# ✅ Salin file package.json dan composer.json lebih awal untuk cache
COPY package*.json ./
COPY composer.json composer.lock ./

# ✅ Install Node modules
RUN npm install

# ✅ Salin semua file Laravel ke container
COPY . .

# ✅ Install dependensi Laravel (tanpa dev)
RUN composer install --no-dev --optimize-autoloader

# ✅ Build Vite assets
RUN npm run build

# ✅ Salin .env jika belum ada
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# ✅ Pastikan file database SQLite ada
RUN mkdir -p database && touch database/database.sqlite

# ✅ Cache konfigurasi Laravel
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Migrasi DB dan Jalankan Seeder
RUN php artisan migrate --force || true
RUN php artisan db:seed --class=DatabaseSeeder --force || true

# ✅ Atur permission
RUN chmod -R 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html

# ✅ Aktifkan rewrite Apache
RUN a2enmod rewrite

# ✅ Ubah document root ke `/public`
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# ✅ Izinkan .htaccess aktif
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Buka port default Apache
EXPOSE 80
