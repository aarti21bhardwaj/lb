<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SectionTeacher Entity
 *
 * @property int $id
 * @property int $section_id
 * @property int $teacher_id
 *
 * @property \App\Model\Entity\Section $section
 * @property \App\Model\Entity\Teacher $teacher
 */
class SectionTeacher extends Entity
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
        'section_id' => true,
        'teacher_id' => true,
        'section' => true,
        'teacher' => true
    ];
}
