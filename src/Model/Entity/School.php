<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * School Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Campus[] $campuses
 * @property \App\Model\Entity\Division[] $divisions
 * @property \App\Model\Entity\SchoolUser[] $school_users
 */
class School extends Entity
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
        'logo_image_path' => true,
        'logo_image_name' => true,
        'created' => true,
        'modified' => true,
        'campuses' => true,
        'divisions' => true,
        'school_users' => true
    ];

    protected $_virtual = ['logo_image_url'];
    protected function _getImageUrl()
    {
        // pr('get image url'); die;
        if(isset($this->_properties['logo_image_name']) && !empty($this->_properties['logo_image_name'])) {
            $url = Router::url('/school_logos/'.$this->_properties['logo_image_name'],true);
        }else{
            $url = Router::url('/img/defaultLogo-img.jpeg',true);
        }
            // pr($url); die('ss');
        return $url;

    }
}
