FROM php:8.2-apache

# Install system dependencies & Node.js 18
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3 libsqlite3-dev \
    libpng-dev libonig-dev libxml2-dev curl git gnupg ca-certificates lsb-release \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy dependency files only (caching layer)
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Install PHP & Node dependencies
RUN composer install --no-scripts --no-dev --optimize-autoloader -vvv
RUN npm install
RUN npx mix --mix-config=webpack.mix.cjs --production

# Copy the rest of the application
COPY . .

# Laravel .env and SQLite (optional)
RUN cp .env.example .env || true
RUN mkdir -p database && touch database/database.sqlite

# Laravel config & route cache
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Migrate & seed (safe fail if already exists)
RUN php artisan migrate --force || true
RUN php artisan db:seed --class=DatabaseSeeder --force || true

# Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Apache rewrite + public root
RUN a2enmod rewrite && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

EXPOSE 80
