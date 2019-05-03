<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateGrade $reportTemplateGrade
  */
?>
<!-- <div class="reportTemplateGrades view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateGrade->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportTemplateGrade->has('report_template') ? $this->Html->link($reportTemplateGrade->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateGrade->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $reportTemplateGrade->has('grade') ? $this->Html->link($reportTemplateGrade->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportTemplateGrade->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateGrade->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateGrade->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateGrade->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

