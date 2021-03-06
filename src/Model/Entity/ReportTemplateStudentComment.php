<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateStudentComment Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property int $student_id
 * @property int $teacher_id
 * @property string $teacher_comment
 * @property string $student_comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\Teacher $teacher
 */
class ReportTemplateStudentComment extends Entity
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
        'report_template_id' => true,
        'student_id' => true,
        'teacher_id' => true,
        'teacher_comment' => true,
        'student_comment' => true,
        'created' => true,
        'modified' => true,
        'report_template' => true,
        'student' => true,
        'teacher' => true
    ];
}
