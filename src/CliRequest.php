<?php
/**
 * A Slim 3 middleware enabling a mock GET request to be made through the CLI.
 * Use in the form: php public/index.php /status GET event=true
 *
 * @link      https://github.com/pavlakis/slim-cli
 * @copyright Copyright © 2015 Antonis Pavlakis
 * @license   https://github.com/pavlakis/slim-cli/LICENSE (BSD 3-Clause License)
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

            list($call, $path, $method, $params) = $argv;

            if (strtoupper($method) === 'GET') {
                $this->request = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock([
                    'REQUEST_METHOD'    => 'GET',
                    'REQUEST_URI'       => $path . '?' . $params,
                    'QUERY_STRING'      => $params
                ]));
            }

            unset($argv);
        }

        return $next($this->request, $response);
    }
}