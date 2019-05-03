<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateCourseScore Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property int $course_id
 * @property int $student_id
 * @property bool $is_completed
 * @property string $scale_value_id
 * @property string $comment
 * @property int $created_by
 * @property int $modified_by
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\User $student
 * @property \App\Model\Entity\ScaleValue $scale_value
 */
class ReportTemplateCourseScore extends Entity
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
        'report_template_id' => true,
        'course_id' => true,
        'student_id' => true,
        'is_completed' => true,
        'scale_value_id' => true,
        'comment' => true,
        'created_by' => true,
        'modified_by' => true,
        'report_template' => true,
        'course' => true,
        'student' => true,
        'scale_value' => true
    ];
}
