<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Produto extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('produto');
        $table->addColumn('produto', 'string', ['limit' => 100])
              ->addColumn('foto', 'string', ['limit' => 45])
              ->addColumn('descricao', 'text')
              ->addColumn('valor', 'double')
              ->addColumn('categoria_id', 'integer')
              ->addColumn('empresa_id', 'integer')
              ->addForeignKey('categoria_id', 'categoria', ['id'])
              ->addForeignKey('empresa_id', 'empresa', ['id'])
              ->create();
    }
}
