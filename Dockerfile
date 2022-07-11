ARG PHP_VERSION=7.2

###
# Test
###
FROM ghcr.io/myparcelnl/php-xd:${PHP_VERSION} AS test

COPY composer.json phpunit.xml ./
COPY test/         ./test/
COPY src/          ./src/

RUN composer install --dev

CMD ["vendor/bin/phpunit", "--coverage-clover", "coverage.xml"]


###
# Dev
###
FROM ghcr.io/myparcelnl/php-xd:${PHP_VERSION} AS dev

CMD ["sleep", "infinity"]
