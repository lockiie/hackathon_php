<?php

declare(strict_types=1);

namespace Api\Models;

use Api\Orm\Table;
use Api\Orm\Column;
use Api\Orm\Entity;
use Api\Orm\PrimaryKey;

#[Table(name: 'empresa')]
class Empresa extends Entity
{
    #[PrimaryKey]
    #[Column]
    public int $id;
    #[Column]
    public string $empresa;
    #[Column]
    public string $whatsapp;
}
