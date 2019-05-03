<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContentCategory Entity
 *
 * @property int $id
 * @property string $name
 * @property string $meta
 * @property string $type
 *
 * @property \App\Model\Entity\ContentValue[] $content_values
 * @property \App\Model\Entity\CourseContentCategory[] $course_content_categories
 * @property \App\Model\Entity\UnitContent[] $unit_contents
 * @property \App\Model\Entity\UnitSpecificContent[] $unit_specific_contents
 */
class ContentCategory extends Entity
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
        'name' => true,
        'meta' => true,
        'type' => true,
        'content_values' => true,
        'course_content_categories' => true,
        'unit_contents' => true,
        'assessment_contents' => true,
        'unit_specific_contents' => true,
        'assessment_specific_contents' => true,
        'evidence_contents' => true
    ];
}
