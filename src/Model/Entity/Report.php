<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property int $grade_id
 * @property int $report_page_id
 * @property int $course_id
 * @property int $sort_order
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\ReportPage $report_page
 * @property \App\Model\Entity\Course $course
 */
class Report extends Entity
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
        'report_page_id' => true,
        'course_id' => true,
        'sort_order' => true,
        'created' => true,
        'modified' => true,
        'report_template' => true,
        'grade' => true,
        'report_page' => true,
        'course' => true
    ];
}
