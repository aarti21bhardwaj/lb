<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CampusCourseTeacher Entity
 *
 * @property int $id
 * @property int $campus_course_id
 * @property int $teacher_id
 * @property bool $is_leader
 *
 * @property \App\Model\Entity\CampusCourse $campus_course
 * @property \App\Model\Entity\User $teacher
 */
class CampusCourseTeacher extends Entity
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
        'campus_course_id' => true,
        'teacher_id' => true,
        'is_leader' => true,
        'campus_course' => true,
        'teacher' => true
    ];
}
