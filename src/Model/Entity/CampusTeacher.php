<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CampusTeacher Entity
 *
 * @property int $id
 * @property int $campus_id
 * @property int $teacher_id
 *
 * @property \App\Model\Entity\Campus $campus
 * @property \App\Model\Entity\Teacher $teacher
 */
class CampusTeacher extends Entity
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
        'campus_id' => true,
        'teacher_id' => true,
        'campus' => true,
        'teacher' => true
    ];
}
