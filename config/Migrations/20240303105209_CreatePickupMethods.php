<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePickupMethods extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table("pickup_methods", ["signed" => false]);
        $table->addColumn("name", "string", ["default" => null, "null" => false, "length" => 72])
            ->addColumn("description", "text", ["default" => null, "null" => true, "limit" => MysqlAdapter::BLOB_REGULAR])
            ->create();
    }
}
