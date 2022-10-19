#!/bin/bash

DOCKER_PHP = api-php

OS := $(shell uname)

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

help:
	@echo 'usage: make [target]'
	@echo ''
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run:
	docker network create api-network || true
	U_ID=${UID} docker-compose up -d

stop:
	U_ID=${UID} docker-compose stop

restart:
	${MAKE} stop && ${MAKE} run

build:
	U_ID=${UID} docker-compose build

prepare:
	${MAKE} composer-install

composer-install:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} composer install --no-scripts --no-iteration --optimize-autoloader

migrations:
	U_ID=${UID} docker exec -it --user ${UID}${DOCKER_PHP} bin/console docktine:migrations:migrate -n

php-logs:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} tail -f var/log/dev.log

ssh-php:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} bash

php-fix:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_PHP} php-cs-fixer fix src --rules=@Symfony
