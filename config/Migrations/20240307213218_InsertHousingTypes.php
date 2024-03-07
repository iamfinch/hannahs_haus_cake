<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InsertHousingTypes extends AbstractMigration
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
        $rows = (array) [
            ["id" => 1, "name" => "House"],
            ["id" => 2, "name" => "Apartment"],
            ["id" => 3, "name" => "Other"],
        ];
        $table = $this->table("housing_types");
        if ($this->isMigratingUp()) {
            $table->insert($rows)->saveData();
        } else {
            $this->execute("DELETE FROM housing_types");
        }
    }
}
