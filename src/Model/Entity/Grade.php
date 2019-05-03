<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Grade Entity
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\AssessmentStrand[] $assessment_strands
 * @property \App\Model\Entity\CourseStrand[] $course_strands
 * @property \App\Model\Entity\Course[] $courses
 * @property \App\Model\Entity\DivisionGrade[] $division_grades
 * @property \App\Model\Entity\StandardGrade[] $standard_grades
 * @property \App\Model\Entity\UnitStrand[] $unit_strands
 */
class Grade extends Entity
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
        'sort_order' => true,
        'created' => true,
        'modified' => true,
        'assessment_strands' => true,
        'course_strands' => true,
        'courses' => true,
        'division_grades' => true,
        'standard_grades' => true,
        'unit_strands' => true,
        'grade_impacts' => true,
        'report_templates' => true
    ];
}
