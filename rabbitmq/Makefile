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

ssh-rabbit:
	U_ID=${UID} dcker exec --user -${UID} -it ${RABBIT} bash
