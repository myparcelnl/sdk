ARG PHP_VERSION=8.1

FROM php:${PHP_VERSION}-cli AS base

RUN touch $PHP_INI_DIR/php.ini && \
    apt-get update && \
    apt-get --yes install \
      zsh \
      libzip-dev \
      unzip && \
    docker-php-ext-install zip && \
    curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/bin \
        --filename=composer && \
    chmod +x /usr/bin/composer && \
    # Install XDebug
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    touch /usr/local/etc/php/conf.d/zzz-xdebug.ini && \
    echo 'xdebug.client_host = host.docker.internal' >> /usr/local/etc/php/conf.d/zzz-xdebug.ini && \
    apt-get clean


###
# Test
###
FROM base AS test

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install

COPY src ./src
COPY test ./test
COPY phpunit.xml ./

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/conf.d/00-php.ini" && \
    echo 'xdebug.mode = coverage' >> /usr/local/etc/php/conf.d/zzz-xdebug.ini

ENTRYPOINT ["vendor/bin/phpunit", "--coverage-clover", "coverage.xml"]


###
# Dev
###
FROM base AS dev

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/conf.d/00-php.ini" && \
    echo 'xdebug.mode = coverage,debug' >> /usr/local/etc/php/conf.d/zzz-xdebug.ini

ENTRYPOINT ["sleep", "infinity"]
