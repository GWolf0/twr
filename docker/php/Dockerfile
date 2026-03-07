FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    curl

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    zip

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy only required for caching
COPY composer.json composer.lock .env.docker ./

# Create .env from .env.docker
RUN cp .env.docker .env

# Install dependencies
RUN composer install \
    --optimize-autoloader \
    --no-interaction

# Create required directories and files and permissions
RUN touch database/database.sqlite \
    && mkdir -p storage/app/public/images \
    && chown -R www-data:www-data storage bootstrap/cache database

# Laravel optimization
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

CMD ["php-fpm"]