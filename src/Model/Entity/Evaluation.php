<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property int $assessment_id
 * @property int $section_id
 * @property int $status
 * @property int $scale_id
 *
 * @property \App\Model\Entity\Assessment $assessment
 * @property \App\Model\Entity\Section $section
 * @property \App\Model\Entity\Scale $scale
 * @property \App\Model\Entity\EvaluationFeedback[] $evaluation_feedbacks
 * @property \App\Model\Entity\EvaluationImpactScore[] $evaluation_impact_scores
 * @property \App\Model\Entity\EvaluationStandardScore[] $evaluation_standard_scores
 */
class Evaluation extends Entity
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
        'assessment_id' => true,
        'section_id' => true,
        'status' => true,
        'scale_id' => true,
        'assessment' => true,
        'section' => true,
        'scale' => true,
        'evaluation_feedbacks' => true,
        'evaluation_impact_scores' => true,
        'evaluation_standard_scores' => true
    ];
}
