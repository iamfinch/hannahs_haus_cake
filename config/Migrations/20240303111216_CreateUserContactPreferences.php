<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUserContactPreferences extends AbstractMigration
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
        $table = $this->table("userContactPreferences", ["signed" => false]);
        $table->addColumn("userId", "integer", ["default" => null, "null" => false, "signed" => false])
            ->addForeignKey(
                "userId",
                "users",
                ["id"],
                ["constraint" => "contact_pref_user_id"]
            )
            ->addColumn("contactMethodId", "integer", ["default" => null, "null" => false, "signed" => false])
            ->addForeignKey(
                "contactMethodId",
                "contact_methods",
                ["id"],
                ["constraint" => "contact_pref_method_id"]
            )
            ->create();
    }
}
