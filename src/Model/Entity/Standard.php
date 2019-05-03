<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Standard Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $strand_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $code
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property bool $is_selected
 *
 * @property \App\Model\Entity\Strand $strand
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\Standard $parent_standard
 * @property \App\Model\Entity\Standard[] $child_standards
 * @property \App\Model\Entity\UnitStandard[] $unit_standards
 */
class Standard extends Entity
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
        'strand_id' => true,
        'created' => true,
        'modified' => true,
        'code' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'is_selectable' => true,
        'strand' => true,
        'grade' => true,
        'parent_standard' => true,
        'child_standards' => true,
        'unit_standards' => true,
        'standard_grades' => true,
        'assessment_standards' => true
    ];
}
