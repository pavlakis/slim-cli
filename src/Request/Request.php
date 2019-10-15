<?php

namespace pavlakis\cli\Request;

class Request implements RequestInterface
{
    private $supportedMethods = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'CONNECT',
        'OPTIONS',
        'TRACE',
        'PATCH',
    ];
    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMethodSupported($method)
    {
        return in_array(strtoupper($method), $this->supportedMethods, true);
    }

    /**
     * @param array  $environmentProperties
     *
     * @return \Slim\Http\Request
     */
    public function getMockRequest($environmentProperties)
    {
        return \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock($environmentProperties));
    }
}
