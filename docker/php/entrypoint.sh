#! THIS FILE IS NOT USED !#

#!/bin/sh

# ensure sqlite exists
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

# install deps if missing
if [ ! -d vendor ]; then
  composer install --optimize-autoloader --no-interaction
fi

# vite
npm install
npm run build

# change permissions
chown -R www-data:www-data database storage bootstrap/cache

# run migrations
php artisan migrate --force

# storage link
php artisan storage:link

# start php-fpm
php-fpm