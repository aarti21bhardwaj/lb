<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TermArchiveUnit Entity
 *
 * @property int $id
 * @property int $term_id
 * @property int $unit_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Term $term
 * @property \App\Model\Entity\Unit $unit
 */
class TermArchiveUnit extends Entity
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
        'term_id' => true,
        'unit_id' => true,
        'created' => true,
        'modified' => true,
        'term' => true,
        'unit' => true
    ];
}
