<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateStandard $reportTemplateStandard
  */
?>
<!-- <div class="reportTemplateStandards view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateStandard->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportTemplateStandard->has('report_template') ? $this->Html->link($reportTemplateStandard->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateStandard->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Standard') ?></th>
            <td><?= $reportTemplateStandard->has('standard') ? $this->Html->link($reportTemplateStandard->standard->name, ['controller' => 'Standards', 'action' => 'view', $reportTemplateStandard->standard->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateStandard->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateStandard->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateStandard->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

