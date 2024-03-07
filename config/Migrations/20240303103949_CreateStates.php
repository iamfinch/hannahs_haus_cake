<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateStates extends AbstractMigration
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
        $table = $this->table('states', ["signed" => false]);
        $table->addColumn(
            "countryId",
            "integer",
            [
                "default" => null,
                "null" => false,
                "signed" => false
            ]
        )
        ->addForeignKey(
            "countryId",
            "countries",
            ["id"],
            [
                "delete" => "CASCADE",
                "constraint" => "state_country_id"
            ]
        )
        ->addColumn("name", "string", ["default" => null, "null" => false])
        ->create();
    }
}
