FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip curl mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 80
