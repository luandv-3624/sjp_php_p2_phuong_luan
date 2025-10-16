FROM php:8.2-fpm

RUN apt-get update && apt-get install -y zip unzip curl git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 6001
CMD php artisan websockets:serve --host=0.0.0.0 --port=${PORT}

