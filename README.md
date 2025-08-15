## Cài đặt dependency Laravel
> composer install

## Cài đặt dependency Frontend
> npm install

## Tạo file môi trường .env
> cp .env.example .env

#### Tạo biến môi trường DB trong .env
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=co_working_space_system_dev
    DB_USERNAME=
    DB_PASSWORD=

## Chạy mỉgrate
> php artisan migrate

## Chạy seed data
> php artisan db:seed
