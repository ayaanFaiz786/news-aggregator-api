FROM php:8.1-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN groupadd --force -g 1000 sail

RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 sail

RUN chown -R sail:sail /var/www/