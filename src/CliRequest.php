<?php
/**
 * Pavlakis Slim CLI Request.
 *
 * A Slim 3 middleware enabling a mock HTTP request to be made through the CLI.
 * Use in the form: php public/index.php /status GET event=true
 *
 * @see        https://github.com/pavlakis/slim-cli
 *
 * @copyright   Copyright © 2019 Antonios Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */

namespace pavlakis\cli;

use pavlakis\cli\Request\Request;
use Psr\Http\Message\ResponseInterface;
use pavlakis\cli\Request\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use pavlakis\cli\Environment\EnvironmentProperties;
use pavlakis\cli\Environment\EnvironmentPropertiesInterface;

class CliRequest
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var EnvironmentPropertiesInterface
     */
    private $environment;

    /**
     * @var RequestInterface
     */
    private $requestAdapter;

    /**
     * @param null|EnvironmentPropertiesInterface $environment
     * @param null|RequestInterface               $request
     *
     * @throws Exception\DefaultPropertyExistsException
     */
    public function __construct(EnvironmentPropertiesInterface $environment = null, RequestInterface $request = null)
    {
        // BC compatibility - always include DefaultEnvironment
        if (null === $environment) {
            $environment = new EnvironmentProperties();
        }
        $this->environment = $environment;

        if (null === $request) {
            $request = new Request();
        }
        $this->requestAdapter = $request;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request  PSR7 request object
     * @param ResponseInterface      $response PSR7 response object
     * @param callable               $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        global $argv;

        $this->request = $request;

        if (isset($argv)) {
            $path = $this->get($argv, 1);
            $method = $this->get($argv, 2);
            $params = $this->get($argv, 3);

            if ($this->requestAdapter->isMethodSupported($method)) {
                $this->request = $this->getMockRequest($this->getUri($path, $params), $params, $method);
            }

            unset($argv);
        }

        return $next($this->request, $response);
    }

    /**
     * Exposed for testing.
     *
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a value from an array if exists otherwise return a default value.
     *
     * @param array $argv
     * @param int   $key
     * @param mixed $default
     *
     * @return string
     */
    private function get($argv, $key, $default = '')
    {
        if (!array_key_exists($key, $argv)) {
            return $default;
        }

        return $argv[$key];
    }

    /**
     * Construct the URI if path and params are being passed.
     *
     * @param string $path
     * @param string $params
     *
     * @return string
     */
    private function getUri($path, $params)
    {
        $uri = '/';
        if (strlen($path) > 0) {
            $uri = $path;
        }

        if (strlen($params) > 0) {
            $uri .= '?'.$params;
        }

        return $uri;
    }

    /**
     * @param string $uri
     * @param string $queryString
     * @param string $method
     *
     * @return \Slim\Http\Request
     */
    private function getMockRequest($uri, $queryString, $method)
    {
        $environmentProperties = $this->environment->getProperties([
            'REQUEST_METHOD' => strtoupper($method),
            'REQUEST_URI' => $uri,
            'QUERY_STRING' => $queryString,
        ]);

        return $this->requestAdapter->getMockRequest($environmentProperties);
    }
}
