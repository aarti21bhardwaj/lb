<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Strand Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $learning_area_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $code
 *
 * @property \App\Model\Entity\LearningArea $learning_area
 * @property \App\Model\Entity\CourseStrand[] $course_strands
 * @property \App\Model\Entity\Standard[] $standards
 */
class Strand extends Entity
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
        'learning_area_id' => true,
        'created' => true,
        'modified' => true,
        'code' => true,
        'learning_area' => true,
        'course_strands' => true,
        'standards' => true,
        'unit_strands' => true,
        'assessment_strands' => true,
    ];
}
