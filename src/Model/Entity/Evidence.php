<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evidence Entity
 *
 * @property int $id
 * @property int $student_id
 * @property string $title
 * @property string $description
 * @property string $file_path
 * @property string $file_name
 * @property string $reflection_description
 * @property string $reflection_file_path
 * @property string $reflection_file_name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $student
 * @property \App\Model\Entity\EvidenceSection[] $evidence_sections
 */
class Evidence extends Entity
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
        'student_id' => true,
        'title' => true,
        'description' => true,
        'file_path' => true,
        'file_name' => true,
        'reflection_description' => true,
        'reflection_file_path' => true,
        'reflection_file_name' => true,
        'created' => true,
        'modified' => true,
        'student' => true,
        'evidence_sections' => true,
        'url' => true,
        'evidence_contexts' => true,
        'digital_tool_used'=> true,
        'evidence_contents' => true
    ];
}
