<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateStrand $reportTemplateStrand
  */
?>
<!-- <div class="reportTemplateStrands view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateStrand->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportTemplateStrand->has('report_template') ? $this->Html->link($reportTemplateStrand->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateStrand->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand') ?></th>
            <td><?= $reportTemplateStrand->has('strand') ? $this->Html->link($reportTemplateStrand->strand->name, ['controller' => 'Strands', 'action' => 'view', $reportTemplateStrand->strand->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateStrand->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Id') ?></th>
            <td><?= $this->Number->format($reportTemplateStrand->course_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateStrand->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateStrand->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

