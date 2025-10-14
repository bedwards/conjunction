#!/bin/bash

set -eux

./vendor/bin/phpunit --colors=always --testdox "$@"
