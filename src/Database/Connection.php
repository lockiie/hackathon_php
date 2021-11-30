<?php

declare(strict_types=1);

namespace Api\Database;

use PDO;

class Connection extends PDO
{
    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $pass     
    )
    {
        parent::__construct(
            "mysql:host=$host;dbname=$database",
            $user,
            $pass
        ); 
    }
}