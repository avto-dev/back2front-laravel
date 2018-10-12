<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# Send backend data to frontend for Laravel applications

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Code quality][badge_code_quality]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

Package for sending data from backend to frontend JS variable.

Package a repository of the form `"key" => "value"` and methods for converting data to array and JSON.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/back2front-laravel "^1.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

Laravel 5.5 and above uses Package Auto-Discovery, so doesn't require you to manually register the service-provider. Otherwise you must add the service provider to the `providers` array in `./config/app.php`:

```php
'providers' => [
    // ...
    AvtoDev\BackendToFrontendVariablesStack\StackServiceProvider::class,
]
```

If you wants to disable package service-provider auto discover, just add into your `composer.json` next lines:

```json
{
    "extra": {
        "laravel": {
            "dont-discover": [
                "avto-dev/back2front-laravel"
            ]
        }
    }
}
```
For publish config and assets execute in console next command:
```shell
php artisan vendor:publish --provider="AvtoDev\BackendToFrontendVariablesStack\StackServiceProvider"
```

This command will publish files `config/back2front.php` with basic setting for package and `public/vendor/back2front/front-stack` with JavaScript object for access data.


## Usage

### At backend

To get the stack object at backend you can use global helper:

```php
backToFrontStack();
```

or getting object from service container:

```php
use AvtoDev\BackendToFrontendVariablesStack\Contracts\BackendToFrontendVariablesInterface;

// ...

/** @var BackendToFrontendVariablesInterface $b2f_stack */
$b2f_stack = resolve(BackendToFrontendVariablesInterface::class);
```

##### Methods

BackendToFrontendVariablesStack object provides the following public methods:

Method | Description
------ | ------------
`put($key, $value)` | Set variable in stack. Parameter `key` must be a string
`get($key, [default]): mixed` |  Get value by key
`has($key): bool`   | Check that key exists in storage
`forget($key)`      | Remove item from storage
`toArray(): array`  | Return data in array
`toJson(): string`  | Return data in JSON encoded

Also you can iterate object.

BackendToFrontendVariablesStack supports dot notation in `put`, `get`, `has` and `forget` methods.

```php
backToFrontStack()->put('user.name', 'John Doe');
```

At frontend will object:

```json
{
    "user": {
        "name": "John Doe"
    }
}
```

### At frontend

For output data at frontend you should add following code in your blade-template (preferably in the section `head` of the resulting HTML document):

```html
<script type="text/javascript">
    Object.defineProperty(window, 'DATA_PROPERTY_NAME', {
        writable: false,
        value: {!! backToFrontStack()->toJson() !!}
    });
</script>
```

OR by blade-directive

```html
@back_to_front_data('DATA_PROPERTY_NAME')
```

It creates property with name equals `DATA_PROPERTY_NAME` for superglobal object `window` with early added data.

Default value of DATA_PROPERTY_NAME is 'backend'. If you use custom value and want to use front-stack helper on frontend, than you need call ```window.frontStack.setStackName('custom_name');```
before helper usage.


**Package contains javaScript helper for access to data object.**

Use it you may adding js file at page:

```html
<script src="/vendor/back2front/front-stack.js" type="text/javascript"></script>
```


> **You also can use it as require.js dependency.**

This creates window.frontStack object which provides following methods:

Method | Description
------ | -----------
`get(key, [default])` |  Get value by key. Supports "dot" notation for access to items if in data contains multidimensional arrays.  Returns `undefined` if item don't exists or default value if it set
`has(key): bool` | Check that key exists in storage
`all(): object` | Returns data object

### Example

At backend:

```php
backToFrontStack()->put('user_id', $user->id);
```

At frontend:

```html
<script type="text/javascript">
    console.log(window.frontStack.get('user_id'));
</script>
```

### Testing

For package testing we use `phpunit` framework. Just write into your terminal:

```shell
$ git clone git@github.com:avto-dev/back2front-laravel.git ./back2front-laravel && cd $_
$ composer install
$ composer test
```

### JS testing

For testing JavaScript code using `Mocha` and `Chai` framework.

Run in console `npm test`. Coverage report will in `coverage/coverage.json` and in `coverage/lcov-report/index.html` for humans.

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/back2front-laravel.svg?style=flat-square&longCache=true
[badge_build_status]:https://img.shields.io/scrutinizer/build/g/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180&logo=scrutinizer
[badge_code_quality]:https://img.shields.io/scrutinizer/g/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_coverage]:https://img.shields.io/scrutinizer/coverage/g/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/back2front-laravel.svg?style=flat-square&longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/back2front-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/back2front-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/back2front-laravel/releases
[link_packagist]:https://packagist.org/packages/avto-dev/back2front-laravel
[link_build_status]:https://scrutinizer-ci.com/g/avto-dev/back2front-laravel/build-status/master
[link_coverage]:https://scrutinizer-ci.com/g/avto-dev/back2front-laravel/?branch=master
[link_changes_log]:https://github.com/avto-dev/back2front-laravel/blob/master/CHANGELOG.md
[link_issues]:https://github.com/avto-dev/back2front-laravel/issues
[link_create_issue]:https://github.com/avto-dev/back2front-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/back2front-laravel/commits
[link_pulls]:https://github.com/avto-dev/back2front-laravel/pulls
[link_license]:https://github.com/avto-dev/back2front-laravel/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
