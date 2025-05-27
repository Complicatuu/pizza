FROM php:8.4-rc-apache@sha256:828b91dd7c0d9fcca23e0ebdf147b083b9b007f906317d5cc839043be355703f
WORKDIR /var/www/html
RUN apt-get update -y && apt-get install -y libmariadb-dev libicu-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite