# Use official PHP + Apache base image
FROM php:8.2-apache

# Install dependencies (for Composer and PHP extensions)
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    git \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose port 80 for Render
EXPOSE 80
