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
     * Fields (table schema definition)
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'autoIncrement' => true],
        'userId' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'dogId' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'pickupMethodId' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'dateCreated' => ['type' => 'datetime', 'null' => false],
        'approved' => ['type' => 'string', 'length' => 1, 'null' => false, 'default' => '0'],
        'approvedDate' => ['type' => 'datetime', 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
            'dog_app_user' => [
                'type' => 'foreign',
                'columns' => ['userId'],
                'references' => ['users', 'id'],
                'update' => 'cascade',
                'delete' => 'cascade'
            ],
            'dog_app_dog' => [
                'type' => 'foreign',
                'columns' => ['dogId'],
                'references' => ['dogs', 'id'],
                'update' => 'cascade',
                'delete' => 'cascade'
            ],
            'dog_app_pickup' => [
                'type' => 'foreign',
                'columns' => ['pickupMethodId'],
                'references' => ['pickup_methods', 'id'],
                'update' => 'restrict',
                'delete' => 'restrict'
            ],
        ],
    ];

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
