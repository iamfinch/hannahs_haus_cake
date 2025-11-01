<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatesFixture
 *
 * Provides state/province data for testing user addresses.
 *
 * States (Subset for Testing):
 * - US States: Alabama (1), Alaska (2), California (5)
 * - Canadian Provinces: Alberta (52), British Columbia (53)
 *
 * Note: Production has 64 total states/provinces. This fixture includes
 * only the subset needed for current test scenarios to keep test data lean.
 *
 * Referenced by:
 * - UsersFixture: User 1 & 2 use stateId 1, User 3 uses stateId 2
 */
class StatesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            // US States (countryId: 1)
            [
                'id' => 1,
                'countryId' => 1,
                'name' => 'Alabama',
                'isProvince' => 0,
            ],
            [
                'id' => 2,
                'countryId' => 1,
                'name' => 'Alaska',
                'isProvince' => 0,
            ],
            [
                'id' => 5,
                'countryId' => 1,
                'name' => 'California',
                'isProvince' => 0,
            ],
            // Canadian Provinces (countryId: 2)
            [
                'id' => 52,
                'countryId' => 2,
                'name' => 'Alberta',
                'isProvince' => 1,
            ],
            [
                'id' => 53,
                'countryId' => 2,
                'name' => 'British Columbia',
                'isProvince' => 1,
            ],
        ];
        parent::init();
    }
}
