<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Campus Entity
 *
 * @property int $id
 * @property string $name
 * @property int $school_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\School $school
 * @property \App\Model\Entity\CampusCourse[] $campus_courses
 * @property \App\Model\Entity\CampusTeacher[] $campus_teachers
 * @property \App\Model\Entity\Division[] $divisions
 * @property \App\Model\Entity\Course[] $courses
 */
class Campus extends Entity
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
        'name' => true,
        'school_id' => true,
        'created' => true,
        'modified' => true,
        'school' => true,
        'campus_courses' => true,
        'campus_teachers' => true,
        'divisions' => true,
        'courses' => true
    ];
}
