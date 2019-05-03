<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateImpactScore Entity
 *
 * @property int $id
 * @property int $report_template_impact_id
 * @property int $student_id
 * @property string $comment
 * @property int $scale_value_id
 *
 * @property \App\Model\Entity\ReportTemplateImpact $report_template_impact
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\ScaleValue $scale_value
 */
class ReportTemplateImpactScore extends Entity
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
        'report_template_impact_id' => true,
        'student_id' => true,
        'comment' => true,
        'scale_value_id' => true,
        'report_template_impact' => true,
        'student' => true,
        'scale_value' => true
    ];
}
