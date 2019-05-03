<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvidenceSection Entity
 *
 * @property int $id
 * @property int $evidence_id
 * @property int $section_id
 *
 * @property \App\Model\Entity\Evidence $evidence
 * @property \App\Model\Entity\Section $section
 */
class EvidenceSection extends Entity
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
        'section_id' => true,
        'evidence' => true,
        'section' => true
    ];
}
