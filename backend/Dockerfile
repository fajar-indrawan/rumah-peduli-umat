FROM php:8.2-cli

# Install dependensi sistem dan ekstensi pdo_mysql
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring

# Pasang Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

CMD php artisan serve --host=0.0.0.0 --port=$PORT
