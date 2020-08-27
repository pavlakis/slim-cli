<?php

declare(strict_types=1);

namespace Pavlakis\Cli\Http;

use Slim\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

final class Request
{
    /**
     * @param ServerRequest|ServerRequestInterface $serverRequest
     * @param array                                $attributes
     * @param array                                $addedHeaders
     *
     * @return ServerRequest
     */
    public static function createFromRequest(
        ServerRequest $serverRequest,
        array $attributes = [],
        array $addedHeaders = []
    ): ServerRequest {
        foreach ($addedHeaders as $headerName => $headerValue) {
            $serverRequest = $serverRequest->withAddedHeader($headerName, $headerValue);
        }

        foreach ($attributes as $attribute => $value) {
            $serverRequest = $serverRequest->withAttribute($attribute, $value);
        }

//        var_dump($serverRequest);
//        exit;

        return $serverRequest;
    }
}
