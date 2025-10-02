FROM php:8.4-fpm

# Системные зависимости и PHP расширения
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

WORKDIR /var/www/html

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Копируем весь проект сразу
COPY . /var/www/html

# Устанавливаем зависимости
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Права на файлы
RUN chown -R www-data:www-data /var/www/html /var/www/html/storage /var/www/html/bootstrap/cache

# Для разработки
CMD php artisan serve --host=0.0.0.0 --port=8000
