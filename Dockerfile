FROM php:8.1-fpm

# system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip sqlite3

RUN docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --prefer-dist --no-dev --no-scripts --no-autoloader \
    && composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 9000
CMD ["php-fpm"]
