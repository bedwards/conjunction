#!/bin/sh

set -eux

./test.sh "$@"

vendor/bin/phpstan
vendor/bin/psalm

# sudo pecl install pcov
# php -m | grep -E "pcov|xdebug"
vendor/bin/phpunit --coverage-text "$@"
