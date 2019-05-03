<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateStudentService Entity
 *
 * @property int $id
 * @property int $student_id
 * @property int $special_service_type_id
 *
 * @property \App\Model\Entity\User $student
 * @property \App\Model\Entity\SpecialService $special_service
 */
class ReportTemplateStudentService extends Entity
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
        'student_id' => true,
        'special_service_type_id' => true,
        'student' => true,
        'special_service' => true
    ];
}
