<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Assessment Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $assessment_type_id
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property bool $is_accessible
 * @property bool $is_published
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\AssessmentType $assessment_type
 * @property \App\Model\Entity\AssessmentContent[] $assessment_contents
 * @property \App\Model\Entity\AssessmentImpact[] $assessment_impacts
 * @property \App\Model\Entity\AssessmentReflection[] $assessment_reflections
 * @property \App\Model\Entity\AssessmentResource[] $assessment_resources
 * @property \App\Model\Entity\AssessmentSpecificContent[] $assessment_specific_contents
 * @property \App\Model\Entity\AssessmentStandard[] $assessment_standards
 * @property \App\Model\Entity\AssessmentStrand[] $assessment_strands
 * @property \App\Model\Entity\Standard[] $standards
 * @property \App\Model\Entity\Strand[] $strands
 * @property \App\Model\Entity\Impact[] $impacts
 */
class Assessment extends Entity
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
        'unit_id' => true,
        'assessment_type_id' => true,
        'name' => true,
        'description' => true,
        'start_date' => true,
        'end_date' => true,
        'is_accessible' => true,
        'is_published' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'unit' => true,
        'assessment_type' => true,
        'assessment_contents' => true,
        'assessment_impacts' => true,
        'assessment_reflections' => true,
        'assessment_resources' => true,
        'assessment_specific_contents' => true,
        'assessment_standards' => true,
        'assessment_strands' => true,
        'standards' => true,
        'strands' => true,
        'impacts' => true,
        'old_id' => true,
        'assessment_subtype_id' =>true,
        'is_digital_tool_used' =>true,
        'reflection_url' =>true
    ];
}
