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
RUN apk --no-cache add php7-xmlwriter=7.4.26-r0 \
                       php7-zip=7.4.26-r0 \
                       php7-pdo=7.4.26-r0 \
                       php7-pdo_mysql=7.4.26-r0 \
                       php7-tokenizer=7.4.26-r0 \
                       php7-simplexml=7.4.26-r0 \
                       php7-bcmath=7.4.26-r0 \
                       php7-fileinfo=7.4.26-r0

# Copy in project code and dependencies from composer2 build stage
COPY --chown=nginx --from=composer2 /app /var/www/lanager

RUN chmod -R 777 /var/www/lanager/storage && \
    ln -s /var/www/lanager/storage/app/public /var/www/lanager/public/storage

WORKDIR /var/www/lanager

# Change to non-privileged user
USER nobody
