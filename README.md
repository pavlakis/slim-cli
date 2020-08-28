[![Build Status](https://travis-ci.org/pavlakis/slim-cli.svg)](https://travis-ci.org/pavlakis/slim-cli)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlakis/slim-cli.svg)](https://packagist.org/packages/pavlakis/slim-cli)
[![Latest Stable Version](https://img.shields.io/packagist/v/pavlakis/slim-cli.svg)](https://packagist.org/packages/pavlakis/slim-cli)
[![codecov](https://codecov.io/gh/pavlakis/slim-cli/branch/master/graph/badge.svg)](https://codecov.io/gh/pavlakis/slim-cli)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

 ##### :warning: You are browsing the code of the upcoming pavlakis/slim-cli version 2.0 compatible with Slim 4.
 ##### Version 2 has been a full re-write. This version is not yet stable.

# Slim 4 Framework CLI Request Middleware

This middleware will transform a CLI call into a mock Request.

## Usage

```bash
composer require pavlakis/slim-cli:dev-2.x
```

The following flags are available:

* `--path` and `-p`
* `--method` and `-m`
* `--query` and `-q`
* `--environment` and `-e`

Example:

```bash
php public/index.php --path=/slim-cli --method=GET --query=event=true
```

### Add it in the middleware section of your application

```php
<?php
declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $app->add(\Pavlakis\Cli\CliRequestMiddleware::class);
};
```

> Note: the `-e` flag offers a full override on the mock environment when passed as a valid JSON string
> e.g. php public/index.php --path=/slim-cli --method=GET --query=event=true --environment='{"QUERY_STRING":"event=false","REQUEST_URI":"/slim-cli?event=false"}'
> The above will return: Slim CLI call {"event":"false"}

### Pass a route to test it with

```php

<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world! ');
        return $response;
    });

    $app->get('/slim-cli', function (Request $request, Response $response) {
        $queryParams = $request->getQueryParams();
        $response->getBody()->write('Slim CLI call ' . \json_encode($queryParams));
        return $response;
    });

};

```
