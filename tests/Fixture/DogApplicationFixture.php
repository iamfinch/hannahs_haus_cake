<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DogApplicationFixture
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
            [
                'id' => 1,
                'userId' => 1,
                'dogId' => 1,
                'pickupMethodId' => 1,
                'dateCreated' => '2024-06-03 01:38:23',
                'approved' => 'Lorem ipsum dolor sit amet',
                'approvedDate' => '2024-06-03 01:38:23',
            ],
        ];
        parent::init();
    }
}
