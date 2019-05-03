<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Report $report
  */
?>
<!-- <div class="reports view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($report->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $report->has('report_template') ? $this->Html->link($report->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $report->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $report->has('grade') ? $this->Html->link($report->grade->name, ['controller' => 'Grades', 'action' => 'view', $report->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Report Page') ?></th>
            <td><?= $report->has('report_page') ? $this->Html->link($report->report_page->title, ['controller' => 'ReportPages', 'action' => 'view', $report->report_page->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= $report->has('course') ? $this->Html->link($report->course->name, ['controller' => 'Courses', 'action' => 'view', $report->course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($report->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort Order') ?></th>
            <td><?= $this->Number->format($report->sort_order) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($report->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($report->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

