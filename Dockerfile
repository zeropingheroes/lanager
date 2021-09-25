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

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy in app code with permissions
COPY --chown=www-data:www-data . /var/www

USER www

EXPOSE 9000
CMD ["php-fpm"]
