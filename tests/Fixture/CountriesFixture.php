<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CountriesFixture
 *
 * Provides country data for testing user addresses and location-based features.
 *
 * Countries:
 * - ID 1: United States Of America
 * - ID 2: Canada
 *
 * This fixture matches production data from InsertCountries migration.
 */
class CountriesFixture extends TestFixture
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
                'name' => 'United States Of America',
            ],
            [
                'id' => 2,
                'name' => 'Canada',
            ],
        ];
        parent::init();
    }
}
