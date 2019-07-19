#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

dc_bin := $(shell command -v docker-compose 2> /dev/null)

SHELL = /bin/sh
RUN_APP_ARGS = --rm --user "$(shell id -u):$(shell id -g)" app
RUN_NODE_ARGS = --rm --user "$(shell id -u):$(shell id -g)" node

.PHONY : help build latest install install-js lowest test-php test-js test-js-cover test test-php-cover test-cover shell shell-js clean
.DEFAULT_GOAL : help

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-14s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build docker images, required for current package environment
	$(dc_bin) build

install-js: ## Install JS dependencies
	$(dc_bin) run $(RUN_NODE_ARGS) yarn install

latest: clean install-js ## Install latest php dependencies
	$(dc_bin) run $(RUN_APP_ARGS) composer update -n --ansi --no-suggest --prefer-dist --prefer-stable

install: clean install-js ## Install regular php dependencies
	$(dc_bin) run $(RUN_APP_ARGS) composer update -n --prefer-dist --no-interaction --no-suggest

lowest: clean install-js ## Install lowest php dependencies
	$(dc_bin) run $(RUN_APP_ARGS) composer update -n --ansi --no-suggest --prefer-dist --prefer-lowest

test-php: ## Execute php tests and linters
	$(dc_bin) run $(RUN_APP_ARGS) composer test

test-php-cover: ## Execute php tests with coverage
	$(dc_bin) run --rm --user "0:0" app sh -c 'docker-php-ext-enable xdebug && su $(shell whoami) -s /bin/sh -c "composer phpunit-cover"'

test-js: ## Execute JS tests
	$(dc_bin) run $(RUN_NODE_ARGS) yarn test

test-js-cover: ## Execute php tests with coverage
	$(dc_bin) run $(RUN_NODE_ARGS) yarn test-cover

test: test-php test-js ## Execute all tests and linters

test-cover: test-php-cover test-js-cover ## Execute php tests with coverage

shell-js: ## Start shell into container with php
	$(dc_bin) run -e "PS1=\[\033[1;32m\]\[\033[1;36m\][\u@docker] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]" \
    $(RUN_NODE_ARGS) sh

shell: ## Start shell into container with php
	$(dc_bin) run -e "PS1=\[\033[1;32m\]\[\033[1;36m\][\u@docker] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]" \
    $(RUN_APP_ARGS) sh

clean: ## Remove all dependencies and unimportant files
	-rm -Rf ./composer.lock ./vendor ./coverage ./node_modules ./package-lock.json ./yarn.lock
