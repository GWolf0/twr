FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    curl \
    nodejs \
    npm

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN cp .env.docker .env

RUN touch database/database.sqlite

RUN composer install \
    --optimize-autoloader \
    --no-interaction

RUN chown -R www-data:www-data database storage bootstrap/cache

RUN php artisan migrate:fresh --force \
    && npm install \
    && npm run build \
    && php artisan storage:link

CMD ["php-fpm"]
