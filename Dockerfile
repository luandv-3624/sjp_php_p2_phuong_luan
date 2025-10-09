FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl libonig-dev libxml2-dev supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY ./supervisord.conf /etc/supervisord.conf

EXPOSE 8000 6001

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

