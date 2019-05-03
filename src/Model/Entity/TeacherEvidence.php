<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TeacherEvidence Entity
 *
 * @property int $id
 * @property int $teacher_id
 * @property string $title
 * @property string $description
 * @property string $file_path
 * @property string $file_name
 * @property string $reflection_description
 * @property string $reflection_file_path
 * @property string $reflection_file_name
 * @property bool $digital_tool_used
 * @property string $url
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $teacher
 */
class TeacherEvidence extends Entity
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
        'teacher_id' => true,
        'title' => true,
        'description' => true,
        'file_path' => true,
        'file_name' => true,
        'reflection_description' => true,
        'reflection_file_path' => true,
        'reflection_file_name' => true,
        // 'digital_tool_used' => true,
        'url' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
        'teacher_evidence_sections' => true,
        // 'teacher_evidence_contexts' => true,
        'teacher_evidence_impacts' => true
    ];
}
