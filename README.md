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

## Hướng dẫn sử dụng Swagger
- B1. Tạo hoặc chỉnh sửa file mô tả API trong thư mục: app/Docs
- B2. Chạy lệnh sau trong terminal để tái tạo file tài liệu:
    > php artisan l5-swagger:generate
- B3: Sau khi chạy xong, có thể mở Swagger UI tại:
    > http://localhost:8000/api/documentation