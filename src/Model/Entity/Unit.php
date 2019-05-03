<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Unit Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $template_id
 * @property int $unit_type_id
 * @property int $trans_disciplinary_theme_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\UnitType $unit_type
 * @property \App\Model\Entity\TransDisciplinaryTheme $trans_disciplinary_theme
 * @property \App\Model\Entity\UnitCourse[] $unit_courses
 * @property \App\Model\Entity\UnitImpact[] $unit_impacts
 * @property \App\Model\Entity\UnitOtherGoal[] $unit_other_goals
 * @property \App\Model\Entity\UnitReflection[] $unit_reflections
 * @property \App\Model\Entity\UnitResource[] $unit_resources
 * @property \App\Model\Entity\UnitStandard[] $unit_standards
 * @property \App\Model\Entity\UnitTeacher[] $unit_teachers
 * @property \App\Model\Entity\Course[] $courses
 */
class Unit extends Entity
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
        'start_date' => true,
        'end_date' => true,
        'template_id' => true,
        'is_archived' => true,
        // 'unit_type_id' => true,
        'trans_disciplinary_theme_id' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'template' => true,
        'unit_type' => true,
        'trans_disciplinary_theme' => true,
        'unit_courses' => true,
        'unit_impacts' => true,
        'unit_other_goals' => true,
        'unit_contents' => true,
        'unit_specific_contents' => true,
        'unit_reflections' => true,
        'unit_resources' => true,
        'unit_standards' => true,
        'unit_strands' => true,
        'unit_teachers' => true,
        'assessments' => true,
        'courses' => true,
        'teachers' => true,
        'term_archive_units' => true
    ];
}
