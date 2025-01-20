FROM php:8.2-cli
RUN apt update
RUN apt install zip unzip -y

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Installation de composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer self-update
ENV COMPOSER_ALLOW_SUPERUSER=1
