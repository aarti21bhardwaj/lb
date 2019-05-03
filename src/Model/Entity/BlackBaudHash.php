<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlackBaudHash Entity
 *
 * @property int $id
 * @property string $old_table_name
 * @property string $new_table_name
 * @property string $old_id
 * @property int $new_id
 *
 * @property \App\Model\Entity\Old $old
 * @property \App\Model\Entity\News $news
 */
class BlackBaudHash extends Entity
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
        'old_table_name' => true,
        'new_table_name' => true,
        'old_id' => true,
        'new_id' => true,
        'old' => true,
        'news' => true
    ];
}
