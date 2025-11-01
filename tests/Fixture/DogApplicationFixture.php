<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DogApplicationFixture
 *
 * Provides realistic test data for dog application tests.
 *
 * Applications:
 * - ID 1: Pending application (User 1 → Dog 1) - Tests standard application flow
 * - ID 2: Approved application (User 3 → Dog 2) - Tests approved status and prevents duplicate
 *
 * This fixture models:
 * - User 1 (John) has a pending application for Buddy (Dog 1)
 * - User 3 (Jane) has an approved application for Luna (Dog 2) - which is why Luna is adopted
 */
class DogApplicationFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'dog_application';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            // Pending application - main testing scenario
            [
                'id' => 1,
                'userId' => 1,        // John Doe
                'dogId' => 1,         // Buddy (available dog)
                'pickupMethodId' => 1, // Flexible
                'dateCreated' => '2024-01-05 14:30:00',
                'approved' => '0',    // Pending status
                'approvedDate' => null,
            ],
            // Approved application - for "already applied" and adoption testing
            [
                'id' => 2,
                'userId' => 3,        // Jane Smith
                'dogId' => 2,         // Luna (adopted dog)
                'pickupMethodId' => 2, // Will Pickup
                'dateCreated' => '2024-01-03 10:00:00',
                'approved' => '1',    // Approved status
                'approvedDate' => '2024-01-04 15:00:00',
            ],
        ];
        parent::init();
    }
}
