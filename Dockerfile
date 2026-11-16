FROM php:8.2-cli-alpine

LABEL maintainer="hectorfranco@nowo.tech"

# Development image for Migrations Kit Contracts (tests, coverage, static analysis). Not shipped with the package.
RUN apk add --no-cache bash git unzip curl linux-headers $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["bash"]
