<?php

declare(strict_types=1);

namespace Api\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Psr7\Response;

class LogMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $start = microtime(true);
        $response = $handler->handle($request);
        $end = microtime(true);

        $dateFormat = "d/m/Y | H:i:s";
        $output = "%datetime% | %level_name% | %message%\n";

        $fomatter = new LineFormatter($output, $dateFormat);

        $stream = new StreamHandler('../tmp/request/request' . date("dmY") . '.log');
        $stream->setFormatter($fomatter);

        $logger = new Logger('APP');
        $logger->pushHandler($stream);
        $logger->info(sprintf(
            "[REQUEST METHOD => %s] %s [STATUS CODE => %s] [TIME => %ss]\n",
            $request->getMethod(),
            $request->getUri(),
            $response->getStatusCode(),
            round($end - $start, 2)
        ));


        return $response;
    }
}
