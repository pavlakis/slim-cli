<?php
/**
 * Pavlakis Slim CLI Request
 *
 * A Slim 3 middleware enabling a mock GET request to be made through the CLI.
 * Use in the form: php public/index.php /status GET event=true
 *
 * @link        https://github.com/pavlakis/slim-cli
 * @copyright   Copyright © 2017 Antonis Pavlakis
 * @author      Antonios Pavlakis
 * @author      Bobby DeVeaux (@bobbyjason) Based on Bobby's code from: https://github.com/dvomedia/gulp-skeleton/blob/master/web/index.php
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */
namespace pavlakis\cli;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CliRequest
{

    /**
     * @var ServerRequestInterface
     */
    protected $request = null;

    /**
     * Exposed for testing.
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a value from an array if exists otherwise return a default value
     *
     * @param   array   $argv
     * @param   integer $key
     * @param   mixed   $default
     * @return  string
     */
    private function get($argv, $key, $default = '')
    {
        if (!array_key_exists($key, $argv)) {
            return $default;
        }

        return $argv[$key];
    }

    /**
     * Construct the URI if path and params are being passed
     *
     * @param string $path
     * @param string $params
     * @return string
     */
    private function getUri($path, $params)
    {
        $uri = '/';
        if (strlen($path) > 0) {
            $uri = $path;
        }

        if (strlen($params) > 0) {
            $uri .= '?' . $params;
        }

        return $uri;
    }

    /**
     * Invoke middleware
     *
     * @param  ServerRequestInterface   $request  PSR7 request object
     * @param  ResponseInterface        $response PSR7 response object
     * @param  callable                 $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        global $argv;

        $this->request = $request;

        if (isset($argv)) {

            $path   = $this->get($argv, 1);
            $method = $this->get($argv, 2);
            $params = $this->get($argv, 3);

            if (strtoupper($method) === 'GET') {
                $this->request = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock([
                    'REQUEST_METHOD'    => 'GET',
                    'REQUEST_URI'       => $this->getUri($path, $params),
                    'QUERY_STRING'      => $params
                ]));
            }

            unset($argv);
        }

        return $next($this->request, $response);
    }
}