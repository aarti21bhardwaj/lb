<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CourseStrand Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $strand_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $grade_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\Strand $strand
 */
class CourseStrand extends Entity
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
        'course_id' => true,
        'strand_id' => true,
        'created' => true,
        'modified' => true,
        'grade_id' => true,
        'course' => true,
        'strand' => true,
        'grade' => true
    ];
}
