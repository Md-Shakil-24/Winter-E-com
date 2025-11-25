# Dockerfile for Winter E-com
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip opcache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Ensure uploads and data directories exist and are writable
RUN mkdir -p /var/www/html/uploads /var/www/html/data /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/uploads /var/www/html/data /var/www/html/logs \
    && chmod -R 755 /var/www/html/uploads /var/www/html/data /var/www/html/logs

# Install PHP dependencies (if composer.json exists)
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
