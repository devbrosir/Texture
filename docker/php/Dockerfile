FROM php:8.4-fpm

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer:latest \
    /usr/bin/install-php-extensions \
    /usr/local/bin/

RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    gd \
    zip \
    sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

COPY composer.json composer.lock ./

RUN composer config -g repos.packagist composer https://mirror2.chabokan.net/composer/

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data

CMD ["php-fpm"]
