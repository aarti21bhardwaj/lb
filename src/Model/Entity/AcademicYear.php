<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcademicYear Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $school_id
 *
 * @property \App\Model\Entity\Term[] $terms
 */
class AcademicYear extends Entity
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
        'start_date' => true,
        'end_date' => true,
        'created' => true,
        'modified' => true,
        'school_id' => true,
        'terms' => true
    ];
}
