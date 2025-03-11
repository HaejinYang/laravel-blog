## app
FROM php:8.2-fpm-alpine as app

# 기본 WORKDIR: /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql pcntl

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./composer.* .

ENV APP_ENV=local
RUN composer install --prefer-dist \
    $(if [ "$APP_ENV" = "production" ]; then echo "--no-dev"; fi) \
    --no-scripts --no-progress --no-interaction

COPY . .

RUN composer dump-autoload --optimize

## app_dev
FROM app as app_dev

RUN install-php-extensions xdebug

COPY ./php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
