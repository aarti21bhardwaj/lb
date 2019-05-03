<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * UnitResource Entity
 *
 * @property int $id
 * @property int $unit_id
 * @property string $resource_type
 * @property string $description
 * @property string $url
 * @property string $file_path
 * @property string $file_name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Unit $unit
 */
class UnitResource extends Entity
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
        'unit_id' => true,
        'resource_type' => true,
        'description' => true,
        'url' => true,
        'file_path' => true,
        'file_name' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'unit' => true,
        'name' => true,
        'object_identifier' => true,
        'object_name' => true,
        'users' => true
    ];

    protected $_virtual = ['image_url'];
    
    protected function _getImageUrl()
    {
        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/unit_resources/'.$this->_properties['image_name'],true);
        }else{
            $url = Router::url('/img/default-img.jpeg',true);
        }
        return $url;
    }

}
