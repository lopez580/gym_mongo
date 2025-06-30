FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libssl-dev pkg-config git unzip curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer
