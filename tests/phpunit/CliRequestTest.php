<?php
/**
 * Pavlakis Slim CLI Request.
 *
 * @see        https://github.com/pavlakis/slim-cli
 *
 * @copyright   Copyright © 2018 Antonis Pavlakis
 * @author      Antonios Pavlakis
 * @author      Bobby DeVeaux (@bobbyjason) Based on Bobby's code from: https://github.com/dvomedia/gulp-skeleton/blob/master/web/index.php
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */

namespace pavlakis\cli\tests;

use Slim\Http\Uri;
use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use pavlakis\cli\CliRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
class CliRequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $argv;

        $argv[0] = 'cli.php';
        $argv[1] = '/status';
        $argv[2] = 'GET';
        $argv[3] = 'event=true';
    }

    /**
     * Taken from: https://github.com/slimphp/Slim-HttpCache/blob/master/tests/CacheTest.php.
     *
     * @return Request
     */
    public function requestFactory()
    {
        $uri = Uri::createFromString('https://example.com:443/foo/bar?abc=123');
        $headers = new Headers();
        $cookies = [];
        $serverParams = [];
        $body = new Body(fopen('php://temp', 'r+'));

        return new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testCorrectRequestParametersArePassed()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals('event=true', $cliRequest->getRequest()->getUri()->getQuery());
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testMinimalCorrectRequestParametersArePassed()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        unset($GLOBALS['argv'][3]);

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals('', $cliRequest->getRequest()->getUri()->getQuery());
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testRequestPathHasBeenUpdated()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals('/status', $cliRequest->getRequest()->getUri()->getPath());
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testRequestRemainsSameIfNoArgvIsPassed()
    {
        unset($GLOBALS['argv']);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals($req, $cliRequest->getRequest());
    }

    /**
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testRequestWhenNoParamsArePassed()
    {
        unset($GLOBALS['argv'][3]);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals('/status', $cliRequest->getRequest()->getUri()->getPath());
    }

    /**
     * @dataProvider httpMethodDataProvider
     *
     * @param $method
     *
     * @throws \pavlakis\cli\Exception\DefaultPropertyExistsException
     */
    public function testAllHttpMethodsAreAllowed($method)
    {
        global $argv;

        $argv[0] = 'cli.php';
        $argv[1] = '/status';
        $argv[2] = $method;
        $argv[3] = 'event=true';

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        $this->assertEquals('event=true', $cliRequest->getRequest()->getUri()->getQuery());
        $this->assertEquals($method, $cliRequest->getRequest()->getMethod());
    }

    public function httpMethodDataProvider()
    {
        return [
            ['GET'],
            ['HEAD'],
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['CONNECT'],
            ['OPTIONS'],
            ['TRACE'],
            ['PATCH'],
        ];
    }
}
