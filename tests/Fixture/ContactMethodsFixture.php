<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContactMethodsFixture
 *
 * Provides contact method options for user contact preferences.
 *
 * Contact Methods:
 * - ID 1: Email
 * - ID 2: Phone
 * - ID 3: Text/SMS
 */
class ContactMethodsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'contact_methods';

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
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => 'email'],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
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
                'name' => 'Email',
            ],
            [
                'id' => 2,
                'name' => 'Phone',
            ],
            [
                'id' => 3,
                'name' => 'Text',
            ],
        ];
        parent::init();
    }
}
