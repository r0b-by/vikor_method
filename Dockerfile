# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi dan dependensi PHP untuk Laravel + SQLite
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev curl git \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /var/www/html

# Salin semua file ke dalam container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate APP_KEY jika belum diset
# NOTE: Jika Render sudah set APP_KEY via ENV, ini bisa di-skip
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan config:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Jalankan migrasi (pastikan file database.sqlite sudah ada!)
RUN php artisan migrate --force || true

# Permission untuk Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Aktifkan rewrite module di Apache
RUN a2enmod rewrite

# Laravel berjalan di port Apache default (80)
EXPOSE 80
