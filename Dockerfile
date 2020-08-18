FROM php:7.3-apache

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt-get update \
 && apt-get install -y zip libzip-dev nano git \
 && docker-php-ext-configure zip --with-libzip \
 && docker-php-ext-install zip \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www!g' /etc/apache2/sites-available/000-default.conf \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
