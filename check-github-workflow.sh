#!/bin/sh

# sudo pecl install pcov
# php -m | grep -E "pcov|xdebug"
vendor/bin/phpunit \
    --testdox-text reports/testdox.txt \
    --log-junit reports/junit.xml \
    --coverage-clover reports/clover.xml \
    --coverage-text

vendor/bin/phpstan analyse --no-progress --error-format github
vendor/bin/psalm --show-info=true --output-format=github
