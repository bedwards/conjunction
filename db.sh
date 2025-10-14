#!/bin/sh

set -eux

docker exec -it conjunction_mysql mysql -u conjuser -pconjpass conjunction_db

# docker exec -i conjunction_mysql mysql -u conjuser -pconjpass conjunction_db < database/seeds.sql

