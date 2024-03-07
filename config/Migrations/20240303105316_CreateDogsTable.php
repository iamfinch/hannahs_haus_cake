<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateDogsTable extends AbstractMigration
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
        $table = $this->table('dogs', ["signed" => false]);
        $table->addColumn("name", "string", ["default" => null, "null" => false])
            ->addColumn("dateBorn", "datetime")
            ->addColumn("color", "string", ["length" => 72, "default" => null, "null" => false])
            ->addColumn("retired", "boolean", ["default" => false])
            ->addColumn("retiredDate", "datetime", ["default" => null, "null" => true])
            ->addColumn("adopted", "boolean", ["default" => false])
            ->addColumn("adoptedDate", "datetime", ["default" => null, "null" => true])
            ->addColumn("userId", "integer", ['signed' => false, "default" => null, "null" => true])
            ->addForeignKey(
                "userId",
                "users",
                ["id"],
                ["constraint" => "dog_owner_id"]
            )
            ->create();
    }
}
