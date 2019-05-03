<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitTeacher Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $teacher_id
 * @property bool $is_creator
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\Teacher $teacher
 */
class UnitTeacher extends Entity
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
        'teacher_id' => true,
        'is_creator' => true,
        'created' => true,
        'modified' => true,
        'unit' => true,
        'teacher' => true
    ];
}
