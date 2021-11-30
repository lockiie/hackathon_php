<?php

declare(strict_types=1);

namespace Api\Middlewares;

use Predis\Client;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class CacheMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $client = new Client(
            'tcp://redis:6379',
            [
                'parameters' => [
                    'password' => getenv('REDIS_PASS')
                ]
            ]
        );

        $cacheName = $request->getUri()->getPath();
        $chaveValue = $client->get($cacheName);
        if ($chaveValue !== NULL) {
            $response = new Response();
            $response->getBody()->write($chaveValue);
            $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(StatusCodeInterface::STATUS_OK);
        } else {
            $response = $handler->handle($request);
            $client->set($cacheName, (string) $response->getBody());
            $client->expire($cacheName, 10);
        }

        return $response;
    }
}
