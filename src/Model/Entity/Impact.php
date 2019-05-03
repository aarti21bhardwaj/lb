<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Impact Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $impact_category_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 *
 * @property \App\Model\Entity\ImpactCategory $impact_category
 * @property \App\Model\Entity\Impact $parent_impact
 * @property \App\Model\Entity\Impact[] $child_impacts
 */
class Impact extends Entity
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
        'description' => true,
        'impact_category_id' => true,
        'created' => true,
        'modified' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'impact_category' => true,
        'parent_impact' => true,
        'child_impacts' => true,
        'impact_type' => true,
        'assessment_impacts' => true,
        'unit_impacts' => true,
        'is_selectable' => true,
        'grade_impacts' => true
    ];
}
