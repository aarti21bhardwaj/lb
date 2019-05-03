<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GradeContext Entity
 *
 * @property int $id
 * @property int $grade_id
 * @property int $context_id
 *
 * @property \App\Model\Entity\Grade $grade
 * @property \App\Model\Entity\Context $context
 */
class GradeContext extends Entity
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
        'grade_id' => true,
        'context_id' => true,
        'grade' => true,
        'context' => true
    ];
}
