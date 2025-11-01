<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 * Provides realistic test data for user-related tests.
 * All users use password: "password123"
 *
 * Users:
 * - ID 1: Regular user (John Doe) - For standard application testing
 * - ID 2: Admin user (Admin User) - For authorization and admin feature testing
 * - ID 3: Secondary user (Jane Smith) - For relationship and multi-user testing
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
            // Regular user - for standard application testing
            [
                'id' => 1,
                'email' => 'user@example.com',
                'phoneNumber' => '555-0100',
                // Bcrypt hash of "password123"
                'password' => '$2y$10$JxGuuseB/3SS6UFPfeJrO.2gqzip4RHRsJP3vopTHSuhNxLbYhRpe',
                'fname' => 'John',
                'lname' => 'Doe',
                'address1' => '123 Main St',
                'address2' => 'Apt 4B',
                'countryId' => 1,
                'stateId' => 1,
                'zipcode' => 90210,
                'housingTypeId' => 1,
                'hasChildren' => 1,
                'everOwnedDogs' => 1,
                'primaryCareTaker' => 1,
                'isAdmin' => 0,
                'dateCreated' => '2024-01-01 10:00:00',
                'lastModified' => '2024-01-01 10:00:00',
            ],
            // Admin user - for authorization testing
            [
                'id' => 2,
                'email' => 'admin@hannahshaus.com',
                'phoneNumber' => '555-0101',
                'password' => '$2y$10$JxGuuseB/3SS6UFPfeJrO.2gqzip4RHRsJP3vopTHSuhNxLbYhRpe',
                'fname' => 'Admin',
                'lname' => 'User',
                'address1' => '456 Oak Ave',
                'address2' => '',
                'countryId' => 1,
                'stateId' => 1,
                'zipcode' => 90210,
                'housingTypeId' => 1,
                'hasChildren' => 0,
                'everOwnedDogs' => 1,
                'primaryCareTaker' => 1,
                'isAdmin' => 1,
                'dateCreated' => '2024-01-01 09:00:00',
                'lastModified' => '2024-01-01 09:00:00',
            ],
            // Secondary user - for relationship testing and adopted dog ownership
            [
                'id' => 3,
                'email' => 'jane@example.com',
                'phoneNumber' => '555-0102',
                'password' => '$2y$10$JxGuuseB/3SS6UFPfeJrO.2gqzip4RHRsJP3vopTHSuhNxLbYhRpe',
                'fname' => 'Jane',
                'lname' => 'Smith',
                'address1' => '789 Elm St',
                'address2' => 'Suite 200',
                'countryId' => 1,
                'stateId' => 2,
                'zipcode' => 10001,
                'housingTypeId' => 2,
                'hasChildren' => 1,
                'everOwnedDogs' => 0,
                'primaryCareTaker' => 1,
                'isAdmin' => 0,
                'dateCreated' => '2024-01-02 10:00:00',
                'lastModified' => '2024-01-02 10:00:00',
            ],
        ];
        parent::init();
    }
}
