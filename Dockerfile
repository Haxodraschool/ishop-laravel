# Stage 1: Build PHP dependencies
FROM php:8.4-fpm-alpine AS php_builder

WORKDIR /var/www/html

# Install minimal dependencies for composer
RUN apk add --no-cache libzip-dev libpng-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Env for composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy only composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

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
FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# Env for composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install runtime & build dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    icu-libs \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    libxml2 \
    oniguruma \
    # Build-only dependencies
    icu-dev \
    libpng-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    gd \
    zip \
    pdo_mysql \
    bcmath \
    exif \
    pcntl \
    mbstring \
    xml

# Clean up build-only dependencies to reduce size
RUN apk del icu-dev libpng-dev libzip-dev libjpeg-turbo-dev freetype-dev oniguruma-dev libxml2-dev $PHPIZE_DEPS

# Copy code
COPY . .

# Copy composer dependencies from Stage 1
COPY --from=php_builder /var/www/html/vendor ./vendor

# Copy assets from Stage 2
COPY --from=assets_builder /app/public/build ./public/build

# Finish composer autoload
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --ignore-platform-reqs

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
