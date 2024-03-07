<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $phoneNumber
 * @property string $password
 * @property string $fname
 * @property string $lname
 * @property int $countryId
 * @property int $stateId
 * @property int $zipcode
 * @property int $housingTypeId
 * @property bool $hasChildren
 * @property bool $everOwnedDogs
 * @property bool $primaryCareTaker
 * @property bool $isAdmin
 * @property \Cake\I18n\FrozenTime $dateCreated
 * @property \Cake\I18n\FrozenTime $lastModified
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'email' => true,
        'phoneNumber' => true,
        'password' => true,
        'fname' => true,
        'lname' => true,
        'countryId' => true,
        'stateId' => true,
        'zipcode' => true,
        'housingTypeId' => true,
        'hasChildren' => true,
        'everOwnedDogs' => true,
        'primaryCareTaker' => true,
        'isAdmin' => true,
        'dateCreated' => true,
        'lastModified' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }
}
