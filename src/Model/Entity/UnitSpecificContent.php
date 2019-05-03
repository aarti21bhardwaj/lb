<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitSpecificContent Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $content_category_id
 * @property string $text
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\ContentCategory $content_category
 */
class UnitSpecificContent extends Entity
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
        'content_category_id' => true,
        'text' => true,
        'unit' => true,
        'content_category' => true
    ];
}
