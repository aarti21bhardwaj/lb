<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitReflection Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property string $description
 * @property int $reflection_category_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $object_identifier
 * @property int $object_name
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\ReflectionCategory $reflection_category
 */
class UnitReflection extends Entity
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
        'description' => true,
        'reflection_category_id' => true,
        'created' => true,
        'modified' => true,
        'object_identifier' => true,
        'object_name' => true,
        'unit' => true,
        'reflection_category' => true,
        'reflection_subcategory_id' => true,
        'users' => true
    ];
}
