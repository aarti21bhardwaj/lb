<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitContent Entity
 *
 * @property int $id
 * @property int $content_category_id
 * @property int $content_value_id
 * @property int $unit_id
 *
 * @property \App\Model\Entity\ContentCategory $content_category
 * @property \App\Model\Entity\ContentValue $content_value
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\AssessmentContent[] $assessment_contents
 */
class UnitContent extends Entity
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
        'content_category_id' => true,
        'content_value_id' => true,
        'unit_id' => true,
        'content_category' => true,
        'content_value' => true,
        'unit' => true,
        'assessment_contents' => true
    ];
}
