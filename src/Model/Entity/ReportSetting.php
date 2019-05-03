<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportSetting Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property int $grade_id
 * @property int $course_id
 * @property bool $course_status
 * @property bool $course_comment_status
 * @property bool $strand_status
 * @property bool $strand_comment_status
 * @property bool $standard_status
 * @property bool $standard_comment_status
 * @property bool $impact_status
 * @property bool $impact_comment_status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $report_template_page_id
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\Course $course
 */
class ReportSetting extends Entity
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
        'grade_id' => true,
        'course_id' => true,
        'course_status' => true,
        'course_comment_status' => true,
        'course_scale_status' => true,
        'strand_status' => true,
        'strand_comment_status' => true,
        'standard_status' => true,
        'standard_comment_status' => true,
        'impact_status' => true,
        'impact_comment_status' => true,
        'show_teacher_reflection' => true,
        'show_student_reflection' => true,
        'show_special_services' => true,
        'created' => true,
        'modified' => true,
        'report_template_page_id' => true,
        'report_template' => true,
        'grade' => true,
        'course' => true
    ];
}
