FROM php:8.4-fpm AS base

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer:latest \
    /usr/bin/install-php-extensions \
    /usr/local/bin/

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    gd \
    zip \
    sockets


FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=modules . /var/www/Base

RUN composer config repo.packagist composer https://mirror.abrha.net/repository/composer/

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

FROM node:lts-alpine3.24 AS builder

WORKDIR /var/www/html

COPY --from=vendor . .

RUN npm install

RUN npm run build

USER www-data

CMD ["php-fpm"]
