FROM composer:2.9 AS composer2

# Copy in project code
COPY . /app

# Install PHP dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-scripts

FROM node:22-alpine AS node22

WORKDIR /app

# Copy in package manifest
COPY package.json package-lock.json /app/

# Install
RUN npm clean-install && npm cache clean --force

# Copy in project code
COPY . /app/

# Build
RUN npm run build

FROM dunglas/frankenphp:1-php8.4-alpine AS base

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    zip \
    pdo_mysql \
    simplexml \
    bcmath \
    gmp

# Copy in app code and Composer packages from composer2 build stage
COPY --from=composer2 /app /app

# Copy in built assets from node22 build stage
COPY --from=node22 /app/public/build /app/public/build/

# Set permissions and enable PHP production settings
RUN chmod -R 777 /app/storage /app/bootstrap/cache && \
    ln -s /app/storage/app/public /app/public/storage && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

FROM base AS dev

# Install xdebug & switch to development PHP configuration
RUN install-php-extensions xdebug && \
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
