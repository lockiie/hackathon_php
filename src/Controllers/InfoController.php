<?php

declare(strict_types=1);

namespace Api\Controllers;

use Api\App\JsonResponse;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class InfoController
{
    public function home(Request $request, Response $response, $args): Response
    {
        $message = ['HACKATHON' => '1.0.0'];

        return JsonResponse::create(
            $response,
            $message,
            StatusCodeInterface::STATUS_OK
        );
    }

    public function server(Request $request, Response $response, $args): Response
    {
        $server = ['IP' => $request->getServerParams()['SERVER_ADDR']];

        return JsonResponse::create(
            $response,
            $server,
            StatusCodeInterface::STATUS_OK
        );
    }
}
