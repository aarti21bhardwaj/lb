<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UnitOtherGoal Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property int $other_goal_category_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Unit $unit
 * @property \App\Model\Entity\OtherGoalCategory $other_goal_category
 */
class UnitOtherGoal extends Entity
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
        'other_goal_category_id' => true,
        'created' => true,
        'modified' => true,
        'unit' => true,
        'other_goal_category' => true
    ];
}
