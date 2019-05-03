<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TeacherEvidenceSection Entity
 *
 * @property int $id
 * @property int $teacher_evidence_id
 * @property int $section_id
 *
 * @property \App\Model\Entity\TeacherEvidence $teacher_evidence
 * @property \App\Model\Entity\Section $section
 */
class TeacherEvidenceSection extends Entity
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
        'teacher_evidence_id' => true,
        'section_id' => true,
        'teacher_evidence' => true,
        'section' => true
    ];
}
