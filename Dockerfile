FROM php:8.1-alpine

ENV COMPOSER_HOME="/tmp/composer"

COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

RUN set -x \
    && apk add --no-cache binutils git \
    && apk add --no-cache --virtual .build-deps linux-headers autoconf pkgconf make g++ gcc 1>/dev/null \
    # install xdebug (for testing with code coverage), but do not enable it
    && pecl install xdebug-3.3.0 1>/dev/null \
    && apk del .build-deps \
    && mkdir /src ${COMPOSER_HOME} \
    && ln -s /usr/bin/composer /usr/bin/c \
    && composer --version \
    && php -v \
    && php -m

WORKDIR /src
