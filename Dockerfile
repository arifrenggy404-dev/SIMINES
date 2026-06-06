FROM php:8.5-cli-alpine

# Install tools dan ekstensi yang diperlukan Laravel 13
RUN apk add --no-cache git unzip bash \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer versi terbaru secara resmi di dalam Docker
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Menyalin seluruh file project ke dalam container
COPY . /app

# Jalankan Composer Install untuk membuat folder vendor khusus PHP 8.4 Linux
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Memastikan izin folder storage dan cache aman untuk Laravel 13
RUN chmod -R 777 /app/storage /app/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8080
