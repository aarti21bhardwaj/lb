<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Division Entity
 *
 * @property int $id
 * @property string $name
 * @property int $school_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $campus_id
 * @property int $template_id
 *
 * @property \App\Model\Entity\School $school
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\DivisionGrade[] $division_grades
 * @property \App\Model\Entity\Term[] $terms
 */
class Division extends Entity
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
        'school_id' => true,
        'created' => true,
        'modified' => true,
        'campus_id' => true,
        'template_id' => true,
        'school' => true,
        'template' => true,
        'division_grades' => true,
        'terms' => true
    ];
}
