<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Action\AddForeignKey;

class UpdateUsersTable extends AbstractMigration
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
        $table = $this->table("users");
        $table->addColumn(
                "countryId",
                "integer",
                [
                    "default" => null,
                    "null" => false,
                    "after" => "lname",
                    "signed" => false
                ]
            )
            ->addForeignKey(
                "countryId",
                "countries",
                ["id"],
                ["constraint" => "user_country_id"]
            )
            ->addColumn(
                "stateId",
                "integer",
                [
                    "after" => "countryId",
                    "null" => false,
                    "default" => null,
                    "signed" => false
                ]
            )
            ->addForeignKey(
                "stateId",
                "states",
                ["id"],
                ["constraint" => "user_state_id"]
            )
            ->addColumn(
                "zipcode",
                "integer",
                [
                    'default' => null,
                    "null" => false,
                    "after" => "stateId"
                ]
            )
            ->addColumn(
                "housingTypeId",
                "integer",
                [
                    "default" => null, 
                    "null" => false,
                    "after" => "zipcode",
                    "signed" => false
                ]
            )
            ->addForeignKey(
                "housingTypeId",
                "housing_types",
                ["id"],
                ["constraint" => "user_housing_type_id"]
            )
            ->update();
        }
}
