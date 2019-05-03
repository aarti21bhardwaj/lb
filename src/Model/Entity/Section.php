<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Section Entity
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property int $teacher_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $term_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\User $teacher
 * @property \App\Model\Entity\Term $term
 * @property \App\Model\Entity\SectionStudent[] $section_students
 * @property \App\Model\Entity\SectionTeacher[] $section_teachers
 * @property \App\Model\Entity\User[] $students
 */
class Section extends Entity
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
        'course_id' => true,
        'teacher_id' => true,
        'created' => true,
        'modified' => true,
        'term_id' => true,
        'course' => true,
        'teacher' => true,
        'term' => true,
        'section_students' => true,
        'section_teachers' => true,
        'students' => true
    ];
}
