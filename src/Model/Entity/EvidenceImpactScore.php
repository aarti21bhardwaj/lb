<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvidenceImpactScore Entity
 *
 * @property int $id
 * @property int $evidence_impact_id
 * @property int $scale_value_id
 * @property string $comment
 * @property bool $is_completed
 *
 * @property \App\Model\Entity\EvidenceImpact $evidence_impact
 * @property \App\Model\Entity\ScaleValue $scale_value
 */
class EvidenceImpactScore extends Entity
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
        'evidence_impact_id' => true,
        'scale_value_id' => true,
        'comment' => true,
        'is_completed' => true,
        'evidence_impact' => true,
        'scale_value' => true
    ];
}
