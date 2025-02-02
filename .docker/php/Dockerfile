# syntax=docker/dockerfile:1.4

FROM php:8.3-fpm-alpine AS base

ARG TIMEZONE=UTC

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apk add --no-cache bash git unzip libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev icu-dev \
    oniguruma-dev autoconf linux-headers acl g++ make && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql zip gd intl opcache exif mbstring && \
    pecl install redis && docker-php-ext-enable redis && \
    rm -rf /var/cache/apk/*

RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo ${TIMEZONE} > /etc/timezone \
    && echo "[PHP]\ndate.timezone = \"${TIMEZONE}\"" > /usr/local/etc/php/conf.d/timezone.ini

COPY --from=composer:2.8.4 /usr/bin/composer /usr/local/bin/composer

COPY .docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

WORKDIR /var/www/bs

FROM base AS deps

COPY ../../composer.* ./

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-scripts --no-progress --prefer-dist

FROM base AS app

COPY --from=deps /var/www/bs/vendor /var/www/bs/vendor
COPY ../../ ./

RUN mkdir -p var/cache var/log && chmod -R 777 var && chown -R www-data:www-data /var/www/bs

FROM app AS prod

ENV APP_ENV=prod
ENV APP_DEBUG=0

ENTRYPOINT ["php-fpm"]
CMD ["-F"]

FROM app AS dev

ENV APP_ENV=dev
ENV APP_DEBUG=1

RUN apk add --no-cache vim procps && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/

EXPOSE 9003

ENTRYPOINT ["php-fpm"]
CMD ["-F"]
