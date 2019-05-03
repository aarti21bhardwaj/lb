<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateImpact $reportTemplateImpact
  */
?>
<!-- <div class="reportTemplateImpacts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateImpact->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportTemplateImpact->has('report_template') ? $this->Html->link($reportTemplateImpact->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateImpact->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Impact') ?></th>
            <td><?= $reportTemplateImpact->has('impact') ? $this->Html->link($reportTemplateImpact->impact->name, ['controller' => 'Impacts', 'action' => 'view', $reportTemplateImpact->impact->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateImpact->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Id') ?></th>
            <td><?= $this->Number->format($reportTemplateImpact->course_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateImpact->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateImpact->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

