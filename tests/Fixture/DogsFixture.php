<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DogsFixture
 *
 * Provides realistic test data for dog-related tests.
 *
 * Dogs:
 * - ID 1: Available dog (Buddy) - Can be applied for, main testing scenario
 * - ID 2: Adopted dog (Luna) - Tests "already adopted" rejection logic
 * - ID 3: Retired dog (Max) - Tests "not available" rejection logic
 */
class DogsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            // Available dog - main testing scenario
            [
                'id' => 1,
                'name' => 'Buddy',
                'dateBorn' => '2021-03-15 00:00:00',
                'color' => 'Golden',
                'retired' => 0,
                'retiredDate' => null,
                'adopted' => 0,
                'adoptedDate' => null,
                'userId' => null,
            ],
            // Adopted dog - test "already adopted" scenario
            [
                'id' => 2,
                'name' => 'Luna',
                'dateBorn' => '2022-06-20 00:00:00',
                'color' => 'Black',
                'retired' => 0,
                'retiredDate' => null,
                'adopted' => 1,
                'adoptedDate' => '2024-01-04 15:00:00',
                'userId' => 3, // Adopted by Jane Smith (user ID 3)
            ],
            // Retired dog - test "not available" scenario
            [
                'id' => 3,
                'name' => 'Max',
                'dateBorn' => '2016-09-10 00:00:00',
                'color' => 'Brown and White',
                'retired' => 1,
                'retiredDate' => '2024-01-10 10:00:00',
                'adopted' => 0,
                'adoptedDate' => null,
                'userId' => null,
            ],
        ];
        parent::init();
    }
}
