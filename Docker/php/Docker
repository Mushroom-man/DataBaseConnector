FROM php:7.1-fpm

RUN usermod -u 1000 www-data

RUN docker-php-ext-install pdo pdo_mysql

VOLUME ["/var/www/pfr"]

WORKDIR /var/www/pfr