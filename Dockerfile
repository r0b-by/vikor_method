# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install dependensi sistem untuk Laravel, SQLite, Node, dan Vite
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev curl git \
    gnupg ca-certificates lsb-release \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin semua file proyek ke dalam container
COPY . .

# Install dependensi Laravel tanpa dev
RUN composer install --no-dev --optimize-autoloader

# Install dan build asset frontend dengan Vite
COPY package*.json ./
RUN npm install && npm run build

# Salin .env jika belum ada
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Laravel: cache config, route, view
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Jalankan migrasi (opsional, skip error kalau DB belum siap)
RUN php artisan migrate --force || true

# Atur permission untuk Laravel
RUN chmod -R 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Aktifkan modul rewrite Apache
RUN a2enmod rewrite

# Ganti DocumentRoot Apache ke /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Izinkan .htaccess aktif untuk routing Laravel
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Buka port Apache default
EXPOSE 80
