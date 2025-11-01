<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PickupMethodsFixture
 *
 * Provides realistic test data for pickup method selection in dog applications.
 *
 * Pickup Methods:
 * - ID 1: Flexible - Open to either pickup or delivery
 * - ID 2: Will Pickup - Adopter will pick up at designated location
 * - ID 3: Requires Delivery - Adopter requires delivery to their location
 *
 * This fixture matches production data from InsertPickupMethods migration.
 */
class PickupMethodsFixture extends TestFixture
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
                'name' => 'Flexible',
                'description' => 'Open to either pickup or delivery arrangements',
            ],
            [
                'id' => 2,
                'name' => 'Will Pickup',
                'description' => 'Adopter will pick up the dog at a designated location',
            ],
            [
                'id' => 3,
                'name' => 'Requires Delivery',
                'description' => 'Adopter requires the dog to be delivered to their location',
            ],
        ];
        parent::init();
    }
}
