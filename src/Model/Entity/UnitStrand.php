<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitStrand Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $strand_id
 * @property int $grade_id
 * @property int $course_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\Strand $strand
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\Course $course
 */
class UnitStrand extends Entity
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
        'strand_id' => true,
        'grade_id' => true,
        'course_id' => true,
        'created' => true,
        'modified' => true,
        'unit' => true,
        'strand' => true,
        'grade' => true,
        'course' => true
    ];
}
