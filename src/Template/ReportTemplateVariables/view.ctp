<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateVariable $reportTemplateVariable
  */
?>
<!-- <div class="reportTemplateVariables view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateVariable->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template Type') ?></th>
            <td><?= $reportTemplateVariable->has('report_template_type') ? $this->Html->link($reportTemplateVariable->report_template_type->id, ['controller' => 'ReportTemplateTypes', 'action' => 'view', $reportTemplateVariable->report_template_type->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($reportTemplateVariable->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Identifier') ?></th>
            <td><?= h($reportTemplateVariable->identifier) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateVariable->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateVariable->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateVariable->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

