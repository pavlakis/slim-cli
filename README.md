[![Build Status](https://travis-ci.org/pavlakis/slim-cli.svg)](https://travis-ci.org/pavlakis/slim-cli)

# Slim 3 Framework CLI Request Middleware

This middleware will transform a CLI call into a GET Request.

### Add it with composer
```
composer require pavlakis/slim-cli
```

### Pass the parameters in this order
`route / method / query string`
```
php public/index.php /status GET event=true
```

### Add it in the middleware section of your application
```
$app->add(new \pavlakis\cli\CliRequest());
```

### Pass a route to test it with

```

$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->setName('status');

```

### Check you're only using a CLI call

```
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
