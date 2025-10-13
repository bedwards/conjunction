#!/bin/sh

set -eux

# --group work
./test.sh "$@"

vendor/bin/phpstan
vendor/bin/psalm

# sudo pecl install pcov
# php -m | grep -E "pcov|xdebug"
# # --group work
vendor/bin/phpunit --coverage-text "$@"
