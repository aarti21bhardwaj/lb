<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CronJob Entity
 *
 * @property int $id
 * @property string $shell_name
 * @property string $method_name
 * @property bool $status
 * @property bool $in_process
 * @property $meta
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CronJob extends Entity
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
        'shell_name' => true,
        'method_name' => true,
        'status' => true,
        'in_process' => true,
        'meta' => true,
        'created' => true,
        'modified' => true
    ];
}
