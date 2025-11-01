<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HousingTypesFixture
 *
 * Provides housing type data for testing user profiles and adoption eligibility.
 *
 * Housing Types:
 * - ID 1: House
 * - ID 2: Apartment
 * - ID 3: Other
 *
 * This fixture matches production data from InsertHousingTypes migration.
 *
 * Referenced by:
 * - UsersFixture: Users 1 & 2 have housingTypeId 1 (House), User 3 has housingTypeId 2 (Apartment)
 */
class HousingTypesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'House',
            ],
            [
                'id' => 2,
                'name' => 'Apartment',
            ],
            [
                'id' => 3,
                'name' => 'Other',
            ],
        ];
        parent::init();
    }
}
