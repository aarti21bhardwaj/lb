<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SectionStudent Entity
 *
 * @property int $id
 * @property int $section_id
 * @property int $student_id
 *
 * @property \App\Model\Entity\Section $section
 * @property \App\Model\Entity\Student $student
 */
class SectionStudent extends Entity
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
        'student_id' => true,
        'section' => true,
        'student' => true
    ];
}
