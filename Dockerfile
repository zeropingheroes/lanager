FROM php:7.4-fpm

# Install package dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    unzip \
    zip \
    netcat \
    libfcgi-bin \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip pcntl bcmath

# Enable PHP FPM status page
RUN set -xe && echo "pm.status_path = /status" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy in app code, only allowing root user to modify
COPY --chown=root:www-data . /var/www

# Change directory to www directory
WORKDIR /var/www

# Install dependencies with Composer
RUN composer install && composer dump-autoload

# Change to non-privileged user
USER www-data

# Check PHP FPM status via script every 30 seconds
HEALTHCHECK --interval=30s --timeout=3s CMD /var/www/docker-php-fpm-healthcheck.sh

# Open PHP-FPM port
EXPOSE 9000

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
