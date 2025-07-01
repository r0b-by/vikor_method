# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi dan dependensi PHP untuk Laravel + SQLite
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev curl git \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Composer dari image resmi Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja ke Laravel root
WORKDIR /var/www/html

# Salin semua file proyek Laravel ke container
COPY . .

# Install dependensi Laravel tanpa dev
RUN composer install --no-dev --optimize-autoloader

# Salin file .env jika belum ada
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Jalankan cache konfigurasi Laravel
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Jalankan migrasi database (opsional, biarkan gagal jika belum siap)
RUN php artisan migrate --force || true

# ✅ Atur permission agar Apache bisa akses Laravel
RUN chmod -R 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html

# ✅ Aktifkan modul rewrite Apache (wajib untuk routing Laravel)
RUN a2enmod rewrite

# ✅ Ubah DocumentRoot Apache agar mengarah ke /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# ✅ Izinkan Apache untuk memproses .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Buka port 80
EXPOSE 80
