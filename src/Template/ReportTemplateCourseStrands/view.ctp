<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplateCourseStrand $reportTemplateCourseStrand
  */
?>
<!-- <div class="reportTemplateCourseStrands view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplateCourseStrand->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportTemplateCourseStrand->has('report_template') ? $this->Html->link($reportTemplateCourseStrand->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateCourseStrand->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= $reportTemplateCourseStrand->has('course') ? $this->Html->link($reportTemplateCourseStrand->course->name, ['controller' => 'Courses', 'action' => 'view', $reportTemplateCourseStrand->course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $reportTemplateCourseStrand->has('grade') ? $this->Html->link($reportTemplateCourseStrand->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportTemplateCourseStrand->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand') ?></th>
            <td><?= $reportTemplateCourseStrand->has('strand') ? $this->Html->link($reportTemplateCourseStrand->strand->name, ['controller' => 'Strands', 'action' => 'view', $reportTemplateCourseStrand->strand->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateCourseStrand->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportTemplateCourseStrand->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportTemplateCourseStrand->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

