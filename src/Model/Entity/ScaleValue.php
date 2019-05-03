<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * ScaleValue Entity
 *
 * @property int $id
 * @property string $name
 * @property int $value
 * @property string $description
 * @property int $scale_id
 * @property int $sort_order
 * @property bool $numeric_value
 * @property bool $is_default
 *
 * @property \App\Model\Entity\Scale $scale
 * @property \App\Model\Entity\EvaluationImpactScore[] $evaluation_impact_scores
 * @property \App\Model\Entity\EvaluationStandardScore[] $evaluation_standard_scores
 */
class ScaleValue extends Entity
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
        'value' => true,
        'description' => true,
        'scale_id' => true,
        'sort_order' => true,
        'numeric_value' => true,
        'is_default' => true,
        'scale' => true,
        'evaluation_impact_scores' => true,
        'evaluation_standard_scores' => true
    ];

    protected $_virtual = ['image_url'];

    protected function _getImageUrl()
    {
        // pr('get image url'); die;
        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/scale_images/'.$this->_properties['image_name'],true);
        }else{
            $url = Router::url('/img/defaultLogo-img.jpeg',true);
        }
            // pr($url); die('ss');
        return $url;

    }
}
