<?php

declare(strict_types=1);

use Api\App\Logger;
use Api\App\Routes;

require_once './../vendor/autoload.php';

$app = Routes::create();
$logger = Logger::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true, $logger);

$app->run();
