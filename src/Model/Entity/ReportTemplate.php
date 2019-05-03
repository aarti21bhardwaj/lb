<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplate Entity
 *
 * @property int $id
 * @property string $name
 * @property int $academic_scale
 * @property int $impact_scale
 * @property int $reporting_period_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReportingPeriod $reporting_period
 * @property \App\Model\Entity\ReportSetting[] $report_settings
 * @property \App\Model\Entity\ReportTemplateCourseStrand[] $report_template_course_strands
 * @property \App\Model\Entity\ReportTemplateGrade[] $report_template_grades
 * @property \App\Model\Entity\ReportTemplateImpact[] $report_template_impacts
 * @property \App\Model\Entity\ReportTemplateStandard[] $report_template_standards
 * @property \App\Model\Entity\ReportTemplateStrand[] $report_template_strands
 * @property \App\Model\Entity\Report[] $reports
 */
class ReportTemplate extends Entity
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
        'academic_scale' => true,
        'impact_scale' => true,
        'reporting_period_id' => true,
        'created' => true,
        'modified' => true,
        'reporting_period' => true,
        'report_settings' => true,
        'report_template_course_strands' => true,
        'report_template_grades' => true,
        'report_template_impacts' => true,
        'report_template_standards' => true,
        'report_template_strands' => true,
        'reports' => true
    ];
}
