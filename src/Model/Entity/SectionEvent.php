<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SectionEvent Entity
 *
 * @property int $id
 * @property int $section_id
 * @property string $name
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property string $object_name
 * @property int $object_identifier
 *
 * @property \App\Model\Entity\Section $section
 */
class SectionEvent extends Entity
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
        'section_id' => true,
        'name' => true,
        'start_date' => true,
        'end_date' => true,
        'object_name' => true,
        'object_identifier' => true,
        'section' => true
    ];
}
