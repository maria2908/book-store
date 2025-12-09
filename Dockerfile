FROM php:8.2-cli

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Install system packages + PHP extensions required by your dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    && docker-php-ext-install zip curl mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN php -v && composer --version && ls -la && composer install --no-ansi --no-interaction --prefer-dist --verbose

# Expose Render port
EXPOSE 10000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
