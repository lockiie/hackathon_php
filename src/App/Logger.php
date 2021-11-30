<?php

declare(strict_types=1);

namespace Api\App;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonoLogger;

class Logger
{
    public static function create(): MonoLogger
    {
        $dateFormat = "d/m/Y | H:i:s";
        $output = "%datetime% | %level_name% | %message%\n";

        $fomatter = new LineFormatter($output, $dateFormat);
        
        $stream = new StreamHandler('../tmp/error/error'.date("dmY").'.log');
        $stream->setFormatter($fomatter);

        $logger = new MonoLogger('APP');
        $logger->pushHandler($stream);

        return $logger;
    }
}
