<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvidenceImpact Entity
 *
 * @property int $id
 * @property int $evidence_id
 * @property int $impact_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Evidence $evidence
 * @property \App\Model\Entity\Impact $impact
 */
class EvidenceImpact extends Entity
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
        'evidence_id' => true,
        'impact_id' => true,
        'created' => true,
        'modified' => true,
        'evidence' => true,
        'impact' => true
    ];
}
