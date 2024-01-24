# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## Unreleased

### Changed

- Minimal PHP version now is `8.1`
- Minimal Laravel version now is `10.0`
- Minimal `phpstan/phpstan` version now is `1.10`
- Minimal `mockery/mockery` version now is `1.6`
- Minimal `phpunit/phpunit` version now is `10.5`
- Version of `composer` in docker container updated up to `2.6.6`

## v2.6.0

### Changed

- Minimal Laravel version now is `9.0`
- Minimal `phpstan/phpstan` version now is `1.9`
- Minimal `mockery/mockery` version now is `1.5.1`
- Minimal `phpunit/phpunit` version now is `9.6`
- Version of `composer` in docker container updated up to `2.5.3`

## v2.5.0

### Removed

Dependency `tarampampam/wrappers-php` because this package was deprecated and removed

## v2.4.0

### Added

- Support PHP `8.x`

### Changed

- Minimal PHP version now is `7.3`
- Composer `2.x` is supported now

## v2.3.0

### Changed

- Laravel `8.x` is supported now
- Minimal Laravel version now is `6.0` (Laravel `5.5` LTS got last security update August 30th, 2020)

## v2.2.0

### Changed

- Maximal `illuminate/*` packages version now is `7.*`
- CI completely moved from "Travis CI" to "Github Actions" _(travis builds disabled)_
- Minimal required PHP version now is `7.2`

### Added

- PHP 7.4 is supported now

## v2.1.0

### Changed

- Maximal `illuminate/*` packages version now is `6.*`

### Added

- GitHub actions for a tests running

## v2.0.0

### Added

- Docker-based environment for development
- Project `Makefile`

### Changed

- Minimal `PHP` version now is `^7.1.3`
- Minimal `Laravel` version now is `5.5.x`
- Maximal `Laravel` version now is `5.8.x`
- Dependency `laravel/framework` changed to `illuminate/*`
- Composer scripts
- Package scripts
- Root namespace from `BackendToFrontendVariablesStack` to `Back2Front`
- `\AvtoDev\BackendToFrontendVariablesStack\StackServiceProvider` &rarr; `\AvtoDev\Back2Front\ServiceProvider`
- `\AvtoDev\BackendToFrontendVariablesStack\Service\BackendToFrontendVariablesStack` &rarr; `\AvtoDev\Back2Front\Back2FrontStack`
- `\AvtoDev\BackendToFrontendVariablesStack\Contracts\BackendToFrontendVariablesInterface` &rarr; `\AvtoDev\Back2Front\Back2FrontInterface`
- Package config, assets and helpers moved into root directory

### Removed

- Dev-dependency `avto-dev/dev-tools`

## v1.1.1

### Changed

- Maximal `phpunit` version now is `7.4.x`. Reason - since `7.5.0` frameworks contains assertions like `assertIsString`, `assertIsArray` and others, already declared in package `avto-dev/dev-tools`

## v1.1.0

### Changed

- Maximal PHP version now is undefined
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

## v1.0.3

### Fixed

- Getting data for blade directive [#2]

[#2]:https://github.com/avto-dev/back2front-laravel/issues/2

## v1.0.2

### Fixed

- Small fixes

## v1.0.1

### Fixed

- Blade directive usage fix

## v1.0.0

### Added

- First release

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
