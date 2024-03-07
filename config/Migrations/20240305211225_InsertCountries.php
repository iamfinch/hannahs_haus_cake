<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InsertCountries extends AbstractMigration
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
        $table = $this->table('countries');
        
        $rows = (array) [
            ["id" => 1, "name" => "United States Of America"],
            ["id" => 2, "name" => "Canada"],
        ];
        if($this->isMigratingUp()) {
            $table->insert($rows)->saveData();
        } else {
            $this->execute("DELETE * FROM countries");
        }
    }
}
