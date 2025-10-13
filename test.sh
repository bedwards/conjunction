#!/bin/bash

set -eux

# --group work
./vendor/bin/phpunit --colors=always --testdox "$@"
