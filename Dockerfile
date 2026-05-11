# Stage 1: Build PHP dependencies
FROM php:8.2-fpm-alpine AS php_builder

WORKDIR /var/www/html

# Install system dependencies for PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    gd \
    zip \
    pdo_mysql \
    bcmath \
    mbstring \
    exif \
    pcntl \
    xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only composer files to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies without scripts to avoid errors with missing code
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# ---
# Stage 2: Build Frontend Assets
FROM node:20-alpine AS assets_builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ---
# Stage 3: Final Production Image
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    icu-libs \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    bash

# Install PHP extensions for runtime
RUN docker-php-ext-install pdo_mysql intl gd zip exif bcmath

# Copy code from main directory
COPY . .

# Copy composer dependencies from Stage 1
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy assets from Stage 2
COPY --from=assets_builder /app/public/build ./public/build

# Finish composer autoload
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize

# Configure Nginx
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Setup Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
