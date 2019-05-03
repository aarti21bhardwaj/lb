<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Term Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $academic_year_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $division_id
 *
 * @property \App\Model\Entity\AcademicYear $academic_year
 * @property \App\Model\Entity\Division $division
 * @property \App\Model\Entity\Section[] $sections
 */
class Term extends Entity
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
        'academic_year_id' => true,
        'created' => true,
        'modified' => true,
        'division_id' => true,
        'academic_year' => true,
        'division' => true,
        'sections' => true,
        'reporting_periods' => true,
        'is_active' => true,
        'term_archive_units' => true

    ];
}
