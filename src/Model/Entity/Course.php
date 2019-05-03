<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Course Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $grade_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $learning_area_id
 *
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\LearningArea $learning_area
 * @property \App\Model\Entity\AssessmentStrand[] $assessment_strands
 * @property \App\Model\Entity\CampusCourse[] $campus_courses
 * @property \App\Model\Entity\CourseContentCategory[] $course_content_categories
 * @property \App\Model\Entity\CourseStrand[] $course_strands
 * @property \App\Model\Entity\Section[] $sections
 * @property \App\Model\Entity\UnitCourse[] $unit_courses
 * @property \App\Model\Entity\UnitStrand[] $unit_strands
 * @property \App\Model\Entity\Unit[] $units
 * @property \App\Model\Entity\ContentCategory[] $content_categories
 */
class Course extends Entity
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
        'description' => true,
        'grade_id' => true,
        'created' => true,
        'modified' => true,
        'learning_area_id' => true,
        'grade' => true,
        'learning_area' => true,
        'assessment_strands' => true,
        'campus_courses' => true,
        'course_content_categories' => true,
        'course_strands' => true,
        'sections' => true,
        'unit_courses' => true,
        'unit_strands' => true,
        'units' => true,
        'content_categories' => true,
        'report_settings' => true,
        'reports' => true,
        'course_grades' => true,
        'report_template_course_scores' => true,
        'report_template_pages' => true,
        'color' => true
    ];
    
    protected $_virtual = ['text'];
    protected function _getText()
    {
        return $this->_properties['name'];
    }
}
