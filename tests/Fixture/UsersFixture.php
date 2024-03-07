<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'email' => 'Lorem ipsum dolor sit amet',
                'phoneNumber' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'fname' => 'Lorem ipsum dolor sit amet',
                'lname' => 'Lorem ipsum dolor sit amet',
                'countryId' => 1,
                'stateId' => 1,
                'zipcode' => 1,
                'housingTypeId' => 1,
                'hasChildren' => 1,
                'everOwnedDogs' => 1,
                'primaryCareTaker' => 1,
                'isAdmin' => 1,
                'dateCreated' => '2024-03-05 20:49:06',
                'lastModified' => '2024-03-05 20:49:06',
            ],
        ];
        parent::init();
    }
}
