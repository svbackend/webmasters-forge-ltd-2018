FROM php:7.2-apache

MAINTAINER Valentyn Saik

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /srv/app

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
RUN a2enmod rewrite
RUN a2enmod headers

RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install mbstring pdo pdo_mysql gd \
    && chown -R www-data:www-data /srv/app