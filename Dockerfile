FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install commonly needed PHP extensions
RUN apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install curl

RUN docker-php-ext-install mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

EXPOSE 80
