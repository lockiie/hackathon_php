<?php

declare(strict_types=1);

namespace Api\App;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function create(
        ResponseInterface $response,
        object|array  $data,
        int $httpCode = StatusCodeInterface::STATUS_OK
    ): ResponseInterface {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($httpCode);
    }
}