# pavlakis/slim-cli

[![Build Status](https://travis-ci.org/pavlakis/slim-cli.svg)](https://travis-ci.org/pavlakis/slim-cli)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlakis/slim-cli.svg)](https://packagist.org/packages/pavlakis/slim-cli)
[![Latest Stable Version](https://img.shields.io/packagist/v/pavlakis/slim-cli.svg)](https://packagist.org/packages/pavlakis/slim-cli)
[![codecov](https://codecov.io/gh/pavlakis/slim-cli/branch/master/graph/badge.svg)](https://codecov.io/gh/pavlakis/slim-cli)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

Slim 3 Framework CLI Request Middleware

This middleware will transform a CLI call into a Request.

Note: Added support for the following HTTP Request methods:

```
    'GET',
    'HEAD',
    'POST',
    'PUT',
    'DELETE',
    'CONNECT',
    'OPTIONS',
    'TRACE',
    'PATCH',
```

## Install

Via Composer

```
composer require pavlakis/slim-cli
```

## Usage

### Pass the parameters in this order
`route / method / query string`

```php
php public/index.php /status GET event=true
```

### Add it in the middleware section of your application

```php
$app->add(new \pavlakis\cli\CliRequest());
```

Adding custom parameters:

```php
$app->add(
	new \pavlakis\cli\CliRequest(
		new EnvironmentProperties(['SERVER_PORT' => 9000])
	)
);
```

### Pass a route to test it with

```php

$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->setName('status');

```

### Check you're only using a CLI call

```php
final class EventStatusAction
{
    ...

    public function dispatch(Request $request, Response $response, $args)
    {

        // ONLY WHEN CALLED THROUGH CLI
        if (PHP_SAPI !== 'cli') {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        if (!$request->getParam('event')) {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        ...

    }

}
```

Or we can use a [PHP Server Interface (SAPI) Middleware](https://github.com/pavlakis/php-server-interface-middleware) to do the SAPI check adding by adding it to a route:

```php
// By default returns a 403 if SAPI not part of the whitelist
$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->add(new Pavlakis\Middleware\Server\Sapi(["cli"]))
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Antonios Pavlakis](https://github.com/pavlakis)

> Based on Bobby DeVeaux's ([@bobbyjason](https://twitter.com/bobbyjason)) [Gulp Skeleton](https://github.com/dvomedia/gulp-skeleton/blob/master/web/index.php)

## License

The BSD 3-Clause License. Please see [License File](LICENSE) for more information.