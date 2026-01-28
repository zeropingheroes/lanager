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

FROM trafex/php-nginx:3.6.0 AS base

USER root

# Install PHP extensions
RUN apk --no-cache add php83-zip=8.3.15-r0 \
                       php83-pdo=8.3.15-r0 \
                       php83-pdo_mysql=8.3.15-r0 \
                       php83-simplexml=8.3.15-r0 \
                       php83-bcmath=8.3.15-r0 \
                       php83-gmp=8.3.15-r0

# Copy in app code and Composer packages from composer2 build stage
COPY --chown=nginx --from=composer2 /app /var/www/lanager

# Copy in built assets from node22 build stage
COPY --chown=nginx --from=node22 /app/public/build /var/www/lanager/public/build/

RUN chmod -R 777 /var/www/lanager/storage /var/www/lanager/bootstrap/cache && \
    ln -s /var/www/lanager/storage/app/public /var/www/lanager/public/storage

WORKDIR /var/www/lanager

# Change to non-privileged user
USER nobody

FROM base AS dev

# Temporary switch to root
USER root

# Install xdebug
RUN apk --no-cache add php83-pecl-xdebug=3.3.2-r0

# Switch back to non-root user
USER nobody
