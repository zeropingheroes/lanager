FROM php:7.4-fpm

WORKDIR /var/www

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

USER www

# TODO: fix permissions issues so container is not run as root
# Set correct permissions for storage directory
#RUN chmod 770 /var/www/storage /var/www/bootstrap/cache && \
#    chown -R www-data:www-data /var/www/
# Change to non-privileged user
#USER www-data

# Open PHP-FPM port
EXPOSE 9000
CMD ["php-fpm"]
