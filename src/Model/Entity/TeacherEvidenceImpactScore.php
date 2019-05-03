<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TeacherEvidenceImpactScore Entity
 *
 * @property int $id
 * @property int $teacher_evidence_impact_id
 * @property int $scale_value_id
 * @property string $comment
 *
 * @property \App\Model\Entity\TeacherEvidenceImpact $teacher_evidence_impact
 * @property \App\Model\Entity\ScaleValue $scale_value
 */
class TeacherEvidenceImpactScore extends Entity
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
        'teacher_evidence_impact_id' => true,
        'scale_value_id' => true,
        'comment' => true,
        'teacher_evidence_impact' => true,
        'scale_value' => true
    ];
}
