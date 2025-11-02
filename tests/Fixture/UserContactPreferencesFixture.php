<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserContactPreferencesFixture
 *
 * Junction table linking users to their preferred contact methods.
 * Many-to-many relationship between Users and ContactMethods.
 *
 * Test Data:
 * - User 1: Prefers Email (ID 1)
 * - User 2 (Admin): Prefers Email (ID 1) and Phone (ID 2)
 * - User 3: Prefers Text (ID 3)
 */
class UserContactPreferencesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'user_contact_preferences';

    /**
     * Import table from database
     *
     * @var bool
     */
    public $import = false;

    /**
     * Fields (table schema definition)
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'autoIncrement' => true],
        'userId' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'contactMethodId' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
            'user_contact_pref_user' => ['type' => 'foreign', 'columns' => ['userId'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict'],
            'user_contact_pref_method' => ['type' => 'foreign', 'columns' => ['contactMethodId'], 'references' => ['contact_methods', 'id'], 'update' => 'restrict', 'delete' => 'restrict'],
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
            [
                'id' => 1,
                'userId' => 1,
                'contactMethodId' => 1, // User 1 - Email
            ],
            [
                'id' => 2,
                'userId' => 2,
                'contactMethodId' => 1, // Admin - Email
            ],
            [
                'id' => 3,
                'userId' => 2,
                'contactMethodId' => 2, // Admin - Phone
            ],
            [
                'id' => 4,
                'userId' => 3,
                'contactMethodId' => 3, // User 3 - Text
            ],
        ];
        parent::init();
    }
}
