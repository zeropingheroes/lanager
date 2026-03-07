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

# Copy entry point
COPY docker/entrypoint.sh /app/docker/entrypoint.sh

# Set a username to use in the image
ARG APP_USER=lanager
ARG APP_UID=1000
ARG APP_GID=1000

# Use PHP configuration for production
# Remove default FrankenPHP capabilities
# Create non-root image user and group
# Give the user write access to caddy and Laravel bootstrap directories
# Ensure the entrypoint script is present and executable
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && setcap -r /usr/local/bin/frankenphp \
    && addgroup -g "${APP_GID}" "${APP_USER}" \
    && adduser -D -u "${APP_UID}" -G "${APP_USER}" "${APP_USER}" \
    && chown -R "${APP_USER}":"${APP_USER}" /config/caddy /data/caddy /app/bootstrap/cache \
    && chmod -R ug+rwX /app/bootstrap/cache \
    && chmod +x /app/docker/entrypoint.sh

# Switch to non-root user
USER ${APP_USER}

ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["php", "artisan", "octane:frankenphp"]

FROM base AS dev

# Switch to root user
USER root

# Install xdebug & switch to development PHP configuration
RUN install-php-extensions xdebug \
    && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Switch to non-root user
USER ${APP_USER}
