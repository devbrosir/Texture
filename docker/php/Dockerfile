FROM php:8.4-fpm AS base

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    curl \
    libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fL \
    --output /usr/local/bin/pie \
    https://github.com/php/pie/releases/latest/download/pie.phar \
    && chmod +x /usr/local/bin/pie

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

RUN pie install imagick/imagick

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=modules . /var/www/Base

RUN composer config repo.packagist composer https://mirror.abrha.net/repository/composer/

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --classmap-authoritative --no-scripts

RUN rm -rf tests node_modules storage/logs/*

RUN chown -R www-data:www-data storage bootstrap/cache

FROM node:lts-alpine3.24 AS builder

WORKDIR /var/www/html

COPY --from=vendor /var/www/html .

RUN npm ci
RUN npm run build

FROM base AS production

WORKDIR /var/www/html

COPY --from=vendor /var/www/html .
COPY --from=builder /var/www/html/public ./public
COPY --from=vendor /var/www/Base /var/www/Base

RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

FROM nginx:alpine AS nginx

COPY --from=production /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
