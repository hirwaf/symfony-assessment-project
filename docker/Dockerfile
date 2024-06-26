# Dockerfile

# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install zip intl pdo_mysql pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-scripts --no-autoloader

# Run Composer autoload generation
RUN composer dump-autoload --optimize

# Expose port and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
