# For other versions check: https://hub.docker.com/_/composer/tags
FROM composer/composer:2.8.12-bin@sha256:f1a57d9cabfc04430058288eaab6c18967b19a3870b5fe71bf0c6afd67968653 AS composer-bin
FROM ghcr.io/php/pie:1.3.1-bin@sha256:560c05d64b4cba4f4c240d25f010533effd8094ebac7075d5daadfb9726e5ebb AS pie-bin
# For other versions check: https://hub.docker.com/_/node/tags?page=1&name=bookworm-slim
FROM node:24.4.1-bookworm-slim@sha256:36ae19f59c91f3303c7a648f07493fe14c4bd91320ac8d898416327bacf1bbfa AS node

# For other versions check: https://hub.docker.com/_/php/tags?page=1&name=fpm-bookworm
FROM php:8.4.15-fpm-bookworm@sha256:0dc3b56694374910c625b6a6cf40c85214ee03c862072438d8deb8dbf09aba42

COPY --from=composer-bin ./composer /usr/bin/composer
COPY --from=pie-bin /pie /usr/bin/pie

ARG USER_NAME=host-user
ARG USER_ID=1000
ARG PHP_FPM_GROUP=www-data

RUN adduser \
        --disabled-password \
        --uid ${USER_ID} \
        ${USER_NAME} \
    && usermod \
        --append \
        --groups \
        ${PHP_FPM_GROUP} \
        ${USER_NAME}

# Add node, npm, npx binaries
COPY --from=node --chown=${USER_NAME}:root /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node --chown=${USER_NAME}:root /usr/local/bin/node /usr/local/bin/node
RUN ln --symbolic /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && chown --no-dereference ${USER_NAME}:root /usr/local/bin/npm \
    && ln --symbolic /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx \
    && chown --no-dereference ${USER_NAME}:root /usr/local/bin/npx

# Use the default development configuration
RUN mv "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"

# For other versions check: http://nginx.org/packages/mainline/debian/pool/nginx/n/nginx/
# renovate: datasource=github-tags depName=nginx/nginx extractVersion=^release-(?<version>.*)$
ARG NGINX_VERSION="1.29.3"

# For other versions check: http://github.com/phpredis/phpredis/releases
# renovate: datasource=github-tags depName=phpredis/phpredis
ARG PHPREDIS_VERSION=6.3.0

RUN apt-get update \
    && apt-get install --assume-yes gpg \
    && curl https://nginx.org/keys/nginx_signing.key | gpg --dearmour --output /etc/apt/trusted.gpg.d/apt.nginx.org.gpg > /dev/null \
    && echo "deb https://nginx.org/packages/mainline/debian bookworm nginx" | tee /etc/apt/sources.list.d/nginx.list \
    && apt-get update && apt-get install --assume-yes \
        nginx=${NGINX_VERSION}-1~bookworm \
        zip \
        libzip-dev \
        libpq-dev \
        zlib1g-dev \
        libjpeg-dev \
        libpng-dev \
        libwebp-dev \
        libfreetype6-dev \
        supervisor \
        cron \
        jpegoptim \
        optipng \
        pngquant \
    && pie install \
            phpredis/phpredis:${PHPREDIS_VERSION} \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        exif \
        gd \
        zip \
        pdo_pgsql

# For other versions check: http://github.com/xdebug/xdebug/releases
# renovate: datasource=github-tags depName=xdebug/xdebug
ARG XDEBUG_VERSION=3.4.7
ARG INSTALL_XDEBUG=true

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pie install xdebug/xdebug:${XDEBUG_VERSION} \
;fi

COPY ./entrypoint.sh /entrypoint.sh

WORKDIR /application

ENTRYPOINT ["/entrypoint.sh"]
