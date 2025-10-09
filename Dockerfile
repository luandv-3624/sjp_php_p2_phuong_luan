FROM php:8.2-fpm

# Cài đặt các gói cần thiết và nginx
RUN apt-get update && apt-get install -y \
    nginx supervisor \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy source
WORKDIR /var/www
COPY . .

# Cài đặt composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Phân quyền cho Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy file config supervisor và nginx
COPY ./supervisord.conf /etc/supervisord.conf
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Tạo thư mục log
RUN mkdir -p /var/log/supervisor

# Expose cổng cho web và websocket
EXPOSE 80 6001

# Start tất cả bằng supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
