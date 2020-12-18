#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

SHELL = /bin/sh
RUN_ARGS = --rm --user "$(shell id -u):$(shell id -g)"

.PHONY : help build latest install install-js lowest test-php test-js test-js-cover test test-php-cover test-cover shell shell-js clean
.DEFAULT_GOAL : help

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-14s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build docker images, required for current package environment
	docker-compose build

install-js: ## Install JS dependencies
	docker-compose run $(RUN_ARGS) node yarn install

latest: clean install-js ## Install latest php dependencies
	docker-compose run $(RUN_ARGS) app composer update -n --ansi --prefer-dist --prefer-stable

install: clean install-js ## Install regular php dependencies
	docker-compose run $(RUN_ARGS) app composer update -n --prefer-dist --no-interaction

lowest: clean install-js ## Install lowest php dependencies
	docker-compose run $(RUN_ARGS) app composer update -n --ansi --prefer-dist --prefer-lowest

test-php: ## Execute php tests and linters
	docker-compose run $(RUN_ARGS) app composer test

test-php-cover: ## Execute php tests with coverage
	docker-compose run --rm --user "0:0" -e 'XDEBUG_MODE=coverage' app sh -c 'docker-php-ext-enable xdebug && su $(shell whoami) -s /bin/sh -c "composer phpunit-cover"'

test-js: ## Execute JS tests
	docker-compose run $(RUN_ARGS) node yarn test

test-js-cover: ## Execute php tests with coverage
	docker-compose run $(RUN_ARGS) node yarn test-cover

test: test-php test-js ## Execute all tests and linters

test-cover: test-php-cover test-js-cover ## Execute php tests with coverage

shell-js: ## Start shell into container with php
	docker-compose run $(RUN_ARGS) node sh

shell: ## Start shell into container with php
	docker-compose run $(RUN_ARGS) app sh

clean: ## Remove all dependencies and unimportant files
	-rm -Rf ./composer.lock ./vendor ./coverage ./node_modules ./package-lock.json ./yarn.lock
