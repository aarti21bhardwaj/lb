<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CourseContentCategory Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $content_category_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\ContentCategory $content_category
 */
class CourseContentCategory extends Entity
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
        'course_id' => true,
        'content_category_id' => true,
        'course' => true,
        'content_category' => true
    ];
}
