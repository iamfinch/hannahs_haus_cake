<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DogApplication Entity
 *
 * @property int $id
 * @property int $userId
 * @property int $dogId
 * @property int $pickupMethodId
 * @property \Cake\I18n\FrozenTime $dateCreated
 * @property string $approved
 * @property \Cake\I18n\FrozenTime|null $approvedDate
 */
class DogApplication extends Entity
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
        'userId' => true,
        'dogId' => true,
        'pickupMethodId' => true,
        'dateCreated' => true,
        'approved' => true,
        'approvedDate' => true,
    ];
}
