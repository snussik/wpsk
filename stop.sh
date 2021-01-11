#!/bin/bash

docker exec DBCONTAINER /usr/bin/mysqldump -u root --password=password exampledb > ./docker/docker-entrypoint-initdb.d/init.sql

docker-compose -f ./docker/stack.yml down -v
