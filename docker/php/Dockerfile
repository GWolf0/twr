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

CMD ["php-fpm"]
