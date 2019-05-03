<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Scale Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Evaluation[] $evaluations
 * @property \App\Model\Entity\ScaleValue[] $scale_values
 */
class Scale extends Entity
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
        'evaluations' => true,
        'scale_values' => true,
        'image_path' => true,
        'image_name' => true
    ];


    protected $_virtual = ['image_url'];

    protected function _getImageUrl()
    {
        // pr('get image url'); die;
        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/profiles/'.$this->_properties['image_name'],true);
        }else{
            $url = NULL;
        }
            // pr($url); die('ss');
        return $url;

    }
}
