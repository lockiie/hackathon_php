<?php

declare(strict_types=1);

namespace Api\Models;

use Api\Orm\Table;
use Api\Orm\Column;
use Api\Orm\Entity;
use Api\Orm\PrimaryKey;

#[Table(name: 'produto')]
class Produto extends Entity
{
    #[PrimaryKey]
    #[Column]
    public int $id;
    #[Column]
    public string $produto;
    #[Column]
    public string $foto;
    #[Column]
    public string $descricao;
    #[Column]
    public float $valor;

    #[Column]
    public int $categoria_id;
    #[Column]
    public int $empresa_id;
}
