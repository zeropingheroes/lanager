FROM composer:2 as composer2

# Copy in project code
COPY . /app

# Install PHP dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-scripts

FROM trafex/php-nginx:2.5.0

USER root

# Install PHP extensions
RUN apk --no-cache add php8-xmlwriter=8.0.24-r0 \
                       php8-zip=8.0.24-r0 \
                       php8-pdo=8.0.24-r0 \
                       php8-pdo_mysql=8.0.24-r0 \
                       php8-tokenizer=8.0.24-r0 \
                       php8-simplexml=8.0.24-r0 \
                       php8-bcmath=8.0.24-r0 \
                       php8-fileinfo=8.0.24-r0

# Copy in app code and Composer packages from composer2 build stage
COPY --chown=nginx --from=composer2 /app /var/www/lanager

RUN chmod -R 777 /var/www/lanager/storage /var/www/lanager/bootstrap/cache && \
    ln -s /var/www/lanager/storage/app/public /var/www/lanager/public/storage

WORKDIR /var/www/lanager

# Change to non-privileged user
USER nobody
