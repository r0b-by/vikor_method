# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependencies & Node.js 18
RUN apt-get update && apt-get install -y \
    zip unzip sqlite3 libzip-dev libpng-dev \
    libonig-dev libxml2-dev curl git gnupg \
    ca-certificates lsb-release libsqlite3-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# Install Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# Salin file dependency terlebih dahulu agar caching efisien
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Install dependency PHP dan Node
RUN composer install --no-dev --optimize-autoloader
RUN npm install

# Jalankan build asset (untuk Vite)
RUN npm run build

# Salin seluruh project
COPY . .

# Salin file .env (jika belum ada)
RUN cp .env.example .env || true

# Buat file database sqlite kosong jika belum ada
RUN mkdir -p database && touch database/database.sqlite

# Laravel config, route dan view cache
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Jalankan migrasi dan seeder (skip error jika sudah)
RUN php artisan migrate --force || true
RUN php artisan db:seed --class=DatabaseSeeder --force || true

# Set permission & ownership
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Aktifkan mod_rewrite & ubah root ke /public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80 (Apache)
EXPOSE 80
