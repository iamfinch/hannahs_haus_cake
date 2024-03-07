<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dog Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $dateBorn
 * @property string $color
 * @property bool $retired
 * @property \Cake\I18n\FrozenTime|null $retiredDate
 * @property bool $adopted
 * @property \Cake\I18n\FrozenTime|null $adoptedDate
 * @property int|null $userId
 */
class Dog extends Entity
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
        'name' => true,
        'dateBorn' => true,
        'color' => true,
        'retired' => true,
        'retiredDate' => true,
        'adopted' => true,
        'adoptedDate' => true,
        'userId' => true,
    ];
}
