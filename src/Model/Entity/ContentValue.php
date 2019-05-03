<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContentValue Entity
 *
 * @property int $id
 * @property int $content_category_id
 * @property string $text
 * @property bool $is_selectable
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ContentCategory $content_category
 * @property \App\Model\Entity\ContentValue $parent_content_value
 * @property \App\Model\Entity\ContentValue[] $child_content_values
 * @property \App\Model\Entity\UnitContent[] $unit_contents
 */
class ContentValue extends Entity
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
        'text' => true,
        'is_selectable' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'created' => true,
        'modified' => true,
        'content_category' => true,
        'parent_content_value' => true,
        'child_content_values' => true,
        'unit_contents' => true,
        'evidence_contents' => true,
        'description' => true
    ];
}
