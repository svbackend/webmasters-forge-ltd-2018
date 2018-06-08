FROM php:7.2-apache

MAINTAINER Valentyn Saik

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /srv/app

RUN apt-get update
RUN a2enmod rewrite
RUN a2enmod headers

RUN docker-php-ext-install mbstring pdo pdo_mysql \
    && chown -R www-data:www-data /srv/app