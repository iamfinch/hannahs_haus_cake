<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Countries',
        'app.States',
        'app.HousingTypes',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test validationDefault method with valid data
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'newuser@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
        ]);

        $this->assertNotFalse($this->Users->save($user), 'User with valid data should save successfully');
        $this->assertEmpty($user->getErrors(), 'User should have no validation errors');
    }

    /**
     * Test that email is required when provided must be valid format
     *
     * @return void
     */
    public function testValidationEmailFormat(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'notanemail',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
        ]);

        $this->assertFalse($this->Users->save($user), 'User with invalid email format should not save');
        $this->assertNotEmpty($user->getError('email'), 'Email field should have validation error');
    }

    /**
     * Test that email must be unique
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // Fixture already has user@example.com
        $user = $this->Users->newEntity([
            'email' => 'user@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Duplicate',
            'lname' => 'User',
            'address1' => '456 Test Ave',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 54321,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
        ]);

        $this->assertFalse($this->Users->save($user), 'User with duplicate email should not save');
        $this->assertNotEmpty($user->getError('email'), 'Email field should have unique constraint error');
    }

    /**
     * Test that password is required on create
     *
     * @return void
     */
    public function testValidationPasswordRequired(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'fname' => 'Test',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // password intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without password should not save');
        $this->assertNotEmpty($user->getError('password'), 'Password field should have validation error');
    }

    /**
     * Test that passwords are automatically hashed
     *
     * @return void
     */
    public function testPasswordHashing(): void
    {
        $plainPassword = 'MySecurePassword123!';
        $user = $this->Users->newEntity([
            'email' => 'hashtest@example.com',
            'password' => $plainPassword,
            'fname' => 'Hash',
            'lname' => 'Test',
            'address1' => '789 Hash Ln',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 99999,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
        ]);

        $this->assertNotFalse($this->Users->save($user), 'User should save successfully');

        // Retrieve from database to verify hashing
        $saved = $this->Users->get($user->id);

        // Assert password is hashed (bcrypt hash starts with $2y$)
        $this->assertStringStartsWith('$2y$', $saved->password, 'Password should be bcrypt hashed');
        $this->assertNotEquals($plainPassword, $saved->password, 'Password should not be stored as plain text');

        // Verify we can authenticate with the plain password
        $hasher = new DefaultPasswordHasher();
        $this->assertTrue(
            $hasher->check($plainPassword, $saved->password),
            'Hashed password should validate against plain text password'
        );
    }

    /**
     * Test that fname is required
     *
     * @return void
     */
    public function testValidationFirstNameRequired(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // fname intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without first name should not save');
        $this->assertNotEmpty($user->getError('fname'), 'First name field should have validation error');
    }

    /**
     * Test that lname is required
     *
     * @return void
     */
    public function testValidationLastNameRequired(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // lname intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without last name should not save');
        $this->assertNotEmpty($user->getError('lname'), 'Last name field should have validation error');
    }

    /**
     * Test that countryId is required
     *
     * @return void
     */
    public function testValidationCountryIdRequired(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // countryId intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without countryId should not save');
        $this->assertNotEmpty($user->getError('countryId'), 'Country ID field should have validation error');
    }

    /**
     * Test that address1 is required
     *
     * @return void
     */
    public function testValidationAddress1Required(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'lname' => 'User',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'zipcode' => 12345,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // address1 intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without address1 should not save');
        $this->assertNotEmpty($user->getError('address1'), 'Address1 field should have validation error');
    }

    /**
     * Test that zipcode is required
     *
     * @return void
     */
    public function testValidationZipcodeRequired(): void
    {
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'fname' => 'Test',
            'lname' => 'User',
            'address1' => '123 Test St',
            'address2' => '',
            'countryId' => 1,
            'stateId' => 1,
            'housingTypeId' => 1,
            'hasChildren' => false,
            'everOwnedDogs' => false,
            'primaryCareTaker' => false,
            'isAdmin' => false,
            'dateCreated' => date('Y-m-d H:i:s'),
            'lastModified' => date('Y-m-d H:i:s'),
            // zipcode intentionally omitted
        ]);

        $this->assertFalse($this->Users->save($user), 'User without zipcode should not save');
        $this->assertNotEmpty($user->getError('zipcode'), 'Zipcode field should have validation error');
    }
}
