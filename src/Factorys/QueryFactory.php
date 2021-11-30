<?php

declare(strict_types=1);

namespace Api\Factorys;

use Api\Database\Connection;
use Api\Orm\Query;

class QueryFactory
{
    public static function getIstance(): Query
    {
        $database = new Connection(
            getenv('DATABASE_HOST'),
            getenv('DATABASE_NAME'),
            getenv('DATABASE_USER'),
            getenv('DATABASE_PASS')
        );

        return new Query($database);
    }
}
