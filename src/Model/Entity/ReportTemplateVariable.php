<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateVariable Entity
 *
 * @property int $id
 * @property int $report_template_type_id
 * @property string $name
 * @property string $identifier
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReportTemplateType $report_template_type
 */
class ReportTemplateVariable extends Entity
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
        'report_template_type_id' => true,
        'name' => true,
        'identifier' => true,
        'created' => true,
        'modified' => true,
        'report_template_type' => true
    ];
}
