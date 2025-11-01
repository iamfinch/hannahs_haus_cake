<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateDogApplications extends AbstractMigration
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
        $table = $this->table("dog_application", ['signed' => false]);
        $table->addColumn("userId", "integer", ["default" => null, "null" => false, "signed" => false])
            ->addForeignKey(
                "userId",
                "users",
                ["id"],
                ["constraint" => "application_user_id"]
            )
            ->addColumn("dogId", "integer", ["default" => null, "null" => false, "signed" => false])
            ->addForeignKey(
                "dogId",
                "dogs",
                ["id"],
                ["constraint" => "application_dog_id"]
            )
            ->addColumn("pickupMethodId", "integer", ["default" => null, "null" => false, "signed" => false])
            ->addForeignKey(
                "pickupMethodId",
                "pickup_methods",
                ["id"],
                ["constraint" => "application_pickup_method_id"]
            )
            ->addColumn("dateCreated", "datetime")
            ->addColumn("approved", "string", ["limit" => 2, "default" => "0", "null" => false, "comment" => "Application status: -1 (rejected), 0 (pending), 1 (approved)"])
            ->addColumn("approvedDate", "datetime", ["default" => null, "null" => true])
            ->create();
    }
}
