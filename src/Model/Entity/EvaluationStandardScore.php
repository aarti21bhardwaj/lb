<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationStandardScore Entity
 *
 * @property int $id
 * @property int $evaluation_id
 * @property int $scale_value_id
 * @property int $student_id
 * @property int $standard_id
 *
 * @property \App\Model\Entity\Evaluation $evaluation
 * @property \App\Model\Entity\ScaleValue $scale_value
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\Standard $standard
 */
class EvaluationStandardScore extends Entity
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
        'evaluation_id' => true,
        'scale_value_id' => true,
        'student_id' => true,
        'standard_id' => true,
        'evaluation' => true,
        'scale_value' => true,
        'student' => true,
        'standard' => true
    ];
}
