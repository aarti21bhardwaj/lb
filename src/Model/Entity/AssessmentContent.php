<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssessmentContent Entity
 *
 * @property int $id
 * @property int $assessment_id
 * @property int $content_category_id
 * @property string $content_value_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $unit_specific_content_id
 *
 * @property \App\Model\Entity\Assessment $assessment
 * @property \App\Model\Entity\ContentCategory $content_category
 * @property \App\Model\Entity\ContentValue $content_value
 * @property \App\Model\Entity\UnitSpecificContent $unit_specific_content
 */
class AssessmentContent extends Entity
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
        'content_category_id' => true,
        'content_value_id' => true,
        'created' => true,
        'modified' => true,
        'unit_specific_content_id' => true,
        'assessment' => true,
        'content_category' => true,
        'content_value' => true,
        'unit_specific_content' => true
    ];
}
