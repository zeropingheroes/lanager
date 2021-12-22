FROM composer:2 as composer2

# Copy in project code
COPY . /app

# Install PHP dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress \
  --no-scripts

FROM trafex/alpine-nginx-php7:1.10.0

USER root

# Install binary dependencies
RUN apk --no-cache add php7-xmlwriter php7-zip php7-pdo php7-pdo_mysql php7-tokenizer php7-simplexml php7-bcmath

# Copy in project code and dependencies from composer2 build stage
COPY --chown=nginx --from=composer2 /app /var/www/lanager

RUN chmod -R 777 /var/www/lanager/storage

WORKDIR /var/www/lanager

# Change to non-privileged user
USER nobody
