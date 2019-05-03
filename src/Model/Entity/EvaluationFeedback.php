<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationFeedback Entity
 *
 * @property int $id
 * @property int $evaluation_id
 * @property int $student_id
 * @property string $comment
 * @property string $numeric_grade
 * @property bool $is_completed
 *
 * @property \App\Model\Entity\Evaluation $evaluation
 * @property \App\Model\Entity\User $student
 */
class EvaluationFeedback extends Entity
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
        'evaluation_id' => true,
        'student_id' => true,
        'comment' => true,
        'numeric_grade' => true,
        'is_completed' => true,
        'evaluation' => true,
        'student' => true
    ];
}
