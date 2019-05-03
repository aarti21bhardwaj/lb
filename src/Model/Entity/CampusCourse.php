<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CampusCourse Entity
 *
 * @property int $id
 * @property int $campus_id
 * @property int $course_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Campus $campus
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\CampusCourseTeacher[] $campus_course_teachers
 */
class CampusCourse extends Entity
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
        'campus_id' => true,
        'course_id' => true,
        'created' => true,
        'modified' => true,
        'campus' => true,
        'course' => true,
        'campus_course_teachers' => true
    ];
}
