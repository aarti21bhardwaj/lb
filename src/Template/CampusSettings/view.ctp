<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\CampusSetting $campusSetting
  */
?>
<!-- <div class="campusSettings view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($campusSetting->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Campus') ?></th>
            <td><?= $campusSetting->has('campus') ? $this->Html->link($campusSetting->campus->name, ['controller' => 'Campuses', 'action' => 'view', $campusSetting->campus->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Setting Key') ?></th>
            <td><?= $campusSetting->has('setting_key') ? $this->Html->link($campusSetting->setting_key->name, ['controller' => 'SettingKeys', 'action' => 'view', $campusSetting->setting_key->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value') ?></th>
            <td><?= h($campusSetting->value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($campusSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($campusSetting->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($campusSetting->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

