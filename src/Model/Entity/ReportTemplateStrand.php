<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateStrand Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property int $course_id
 * @property int $strand_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\Strand $strand
 * @property \App\Model\Entity\ReportTemplateStrandScore[] $report_template_strand_scores
 */
class ReportTemplateStrand extends Entity
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
        'strand_id' => true,
        'grade_id' => true,
        'created' => true,
        'modified' => true,
        'report_template' => true,
        'course' => true,
        'strand' => true,
        'report_template_strand_scores' => true
    ];
}
