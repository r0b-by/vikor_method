# ✅ Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# ✅ Install dependencies sistem dan Node.js 18
RUN apt-get update && apt-get install -y \
    zip unzip sqlite3 libzip-dev libpng-dev \
    libonig-dev libxml2-dev curl git gnupg \
    ca-certificates lsb-release libsqlite3-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# ✅ Install Composer dari container resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Atur working directory
WORKDIR /var/www/html

# ✅ Salin seluruh file project Laravel
COPY . .

# ✅ Siapkan file `.env` dan database SQLite
RUN cp .env.example .env || true && \
    mkdir -p database && \
    touch database/database.sqlite && \
    chmod 664 database/database.sqlite

# ✅ Install dependensi PHP (tanpa dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ✅ Build asset frontend (Vite)
RUN npm install && npm run build

# ✅ Laravel: discover package & cache config/route/view
RUN php artisan package:discover && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ✅ Jalankan migrasi & seeder (tidak hentikan build jika error)
RUN php artisan migrate --force || echo "migrate failed (OK)" && \
    php artisan db:seed --class=DatabaseSeeder --force || echo "seeding failed (OK)"

# ✅ Buat symbolic link ke storage/public
RUN php artisan storage:link || echo "storage link already exists (OK)"

# ✅ Set permission untuk user www-data
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ✅ Aktifkan mod_rewrite dan ubah DocumentRoot ke /public
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ✅ Expose port untuk Apache
EXPOSE 80

# ✅ Start Apache saat container berjalan
CMD ["apache2-foreground"]
