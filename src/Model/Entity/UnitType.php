<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitType Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $type_id
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\Type $type
 */
class UnitType extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'unit_id' => true,
        'type_id' => true,
        'unit' => true,
        'type' => true
    ];
}
