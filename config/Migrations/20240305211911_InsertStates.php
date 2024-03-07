<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InsertStates extends AbstractMigration
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
            ["id" => 1, "name" => "Alabama", "isProvince" => false, "countryId" => 1],
            ["id" => 2, "name" => "Alaska", "isProvince" => false, "countryId" => 1],
            ["id" => 3, "name" => "Arizona", "isProvince" => false, "countryId" => 1],
            ["id" => 4, "name" => "Arkansas", "isProvince" => false, "countryId" => 1],
            ["id" => 5, "name" => "California", "isProvince" => false, "countryId" => 1],
            ["id" => 6, "name" => "Colorado", "isProvince" => false, "countryId" => 1],
            ["id" => 7, "name" => "Connecticut", "isProvince" => false, "countryId" => 1],
            ["id" => 8, "name" => "Delaware", "isProvince" => false, "countryId" => 1],
            ["id" => 9, "name" =>"District Of Columbia", "isProvince" => false, "countryId" => 1],
            ["id" => 10, "name" => "Florida", "isProvince" => false, "countryId" => 1],
            ["id" => 11, "name" => "Georgia", "isProvince" => false, "countryId" => 1],
            ["id" => 12, "name" => "Hawaii", "isProvince" => false, "countryId" => 1],
            ["id" => 13, "name" => "Idaho", "isProvince" => false, "countryId" => 1],
            ["id" => 14, "name" => "Illinois", "isProvince" => false, "countryId" => 1],
            ["id" => 15, "name" => "Indiana", "isProvince" => false, "countryId" => 1],
            ["id" => 16, "name" => "Iowa", "isProvince" => false, "countryId" => 1],
            ["id" => 17, "name" => "Kansas", "isProvince" => false, "countryId" => 1],
            ["id" => 18, "name" => "Kentucky", "isProvince" => false, "countryId" => 1],
            ["id" => 19, "name" => "Louisiana", "isProvince" => false, "countryId" => 1],
            ["id" => 20, "name" => "Maine", "isProvince" => false, "countryId" => 1],
            ["id" => 21, "name" => "Maryland", "isProvince" => false, "countryId" => 1],
            ["id" => 22, "name" => "Massachusetts", "isProvince" => false, "countryId" => 1],
            ["id" => 23, "name" => "Michigan", "isProvince" => false, "countryId" => 1],
            ["id" => 24, "name" => "Minnesota", "isProvince" => false, "countryId" => 1],
            ["id" => 25, "name" => "Mississippi", "isProvince" => false, "countryId" => 1],
            ["id" => 26, "name" => "Missouri", "isProvince" => false, "countryId" => 1],
            ["id" => 27, "name" => "Montana", "isProvince" => false, "countryId" => 1],
            ["id" => 28, "name" => "Nebraska", "isProvince" => false, "countryId" => 1],
            ["id" => 29, "name" => "Nevada", "isProvince" => false, "countryId" => 1],
            ["id" => 30, "name" => "New Hampshire", "isProvince" => false, "countryId" => 1],
            ["id" => 31, "name" => "New Jersey", "isProvince" => false, "countryId" => 1],
            ["id" => 32, "name" => "New Mexico", "isProvince" => false, "countryId" => 1],
            ["id" => 33, "name" => "New York", "isProvince" => false, "countryId" => 1],
            ["id" => 34, "name" => "North Carolina", "isProvince" => false, "countryId" => 1],
            ["id" => 35, "name" => "North Dakota", "isProvince" => false, "countryId" => 1],
            ["id" => 36, "name" => "Ohio", "isProvince" => false, "countryId" => 1],
            ["id" => 37, "name" => "Oklahoma", "isProvince" => false, "countryId" => 1],
            ["id" => 38, "name" => "Oregon", "isProvince" => false, "countryId" => 1],
            ["id" => 39, "name" => "Pennsylvania", "isProvince" => false, "countryId" => 1],
            ["id" => 40, "name" => "Rhode Island", "isProvince" => false, "countryId" => 1],
            ["id" => 41, "name" => "South Carolina", "isProvince" => false, "countryId" => 1],
            ["id" => 42, "name" => "South Dakota", "isProvince" => false, "countryId" => 1],
            ["id" => 43, "name" => "Tennessee", "isProvince" => false, "countryId" => 1],
            ["id" => 44, "name" => "Texas", "isProvince" => false, "countryId" => 1],
            ["id" => 45, "name" => "Utah", "isProvince" => false, "countryId" => 1],
            ["id" => 46, "name" => "Vermont", "isProvince" => false, "countryId" => 1],
            ["id" => 47, "name" => "Virginia", "isProvince" => false, "countryId" => 1],
            ["id" => 48, "name" => "Washington", "isProvince" => false, "countryId" => 1],
            ["id" => 49, "name" => "West Virginia", "isProvince" => false, "countryId" => 1],
            ["id" => 50, "name" => "Wisconsin", "isProvince" => false, "countryId" => 1],
            ["id" => 51, "name" => "Wyoming", "isProvince" => false, "countryId" => 1],
            ["id" => 52, "name" => "Alberta", "isProvince" => true, "countryId" => 2],
            ["id" => 53, "name" => "British Columbia", "isProvince" => true, "countryId" => 2],
            ["id" => 54, "name" => "Manitoba", "isProvince" => true, "countryId" => 2],
            ["id" => 55, "name" => "New Brunswick", "isProvince" => true, "countryId" => 2],
            ["id" => 56, "name" => "Newfoundland and Labrador", "isProvince" => true, "countryId" => 2],
            ["id" => 57, "name" => "Nova Scotia", "isProvince" => true, "countryId" => 2],
            ["id" => 58, "name" => "Northwest Territories", "isProvince" => true, "countryId" => 2],
            ["id" => 59, "name" => "Nunavut", "isProvince" => true, "countryId" => 2],
            ["id" => 60, "name" => "Ontario", "isProvince" => true, "countryId" => 2],
            ["id" => 61, "name" => "Prince Edward Island", "isProvince" => true, "countryId" => 2],
            ["id" => 62, "name" => "Quebec", "isProvince" => true, "countryId" => 2],
            ["id" => 63, "name" => "Saskatchewan", "isProvince" => true, "countryId" => 2],
            ["id" => 64, "name" => "Yukon", "isProvince" => true, "countryId" => 2],
        ];

        $table = $this->table('states');
        $table->addColumn('isProvince', 'boolean', ["default" => false])
            ->update();

        if ($this->isMigratingUp()) {
            $table->insert($rows)->saveData();
        } else {
            $this->execute("DELETE FROM states");
        }
    }
}
