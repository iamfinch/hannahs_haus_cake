<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsersTable extends AbstractMigration
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
        $table = $this->table('users', ["signed" => false]);
        $table->addColumn(
                'email',
                'string',
                [
                    "default" => null,
                    'length' => 255,
                    'null' => true
                ])
            ->addColumn(
                "phoneNumber",
                'string',
                [
                    "default" => null,
                    "null" => true,
                ])
            ->addColumn(
                "password",
                "string",
                [
                    "default" => null,
                    "null" => false,
                    "length" => 72
                ]
            )
            ->addColumn(
                "fname",
                "string",
                [
                    "default" => null,
                    "null" => false,
                    "length" => 72
                ]
            )
            ->addColumn(
                "lname",
                "string",
                [
                    "default" => null,
                    "null" => false,
                    "length" => 72
                ]
            )
            ->addColumn("hasChildren", "boolean", ['default' => false])
            ->addColumn("everOwnedDogs", "boolean", ["default" => false])
            ->addColumn("primaryCareTaker", "boolean", ["default" => false])
            ->addColumn("isAdmin", "boolean", ["default" => false])
            ->addColumn("dateCreated", "datetime")
            ->addColumn("lastModified", "datetime")
            ->create();
    }
}
