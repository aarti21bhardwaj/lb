<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportTemplateEmailSetting Entity
 *
 * @property int $id
 * @property int $report_template_id
 * @property string $sender_email
 * @property string $body
 * @property bool $live_mode
 * @property string $test_receiver_email
 *
 * @property \App\Model\Entity\ReportTemplate $report_template
 */
class ReportTemplateEmailSetting extends Entity
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
        'report_template_id' => true,
        'sender_email' => true,
        'body' => true,
        'live_mode' => true,
        'test_receiver_email' => true,
        'report_template' => true
    ];
}
