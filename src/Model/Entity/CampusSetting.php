<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CampusSetting Entity
 *
 * @property int $id
 * @property int $campus_id
 * @property int $setting_key_id
 * @property string $value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Campus $campus
 * @property \App\Model\Entity\SettingKey $setting_key
 */
class CampusSetting extends Entity
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
        'setting_key_id' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
        'campus' => true,
        'setting_key' => true
    ];
}
