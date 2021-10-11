FROM php:7.4-fpm

# Install package dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    unzip \
    zip \
    netcat \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy in app code with permissions
COPY --chown=www-data:www-data . /var/www

# Change directory to www directory
WORKDIR /var/www

# Install dependencies with Composer
RUN composer install && composer dump-autoload

# Change to non-privileged user
USER www-data

# Open PHP-FPM port
EXPOSE 9000

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
