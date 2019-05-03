<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportSetting $reportSetting
  */
?>
<!-- <div class="reportSettings view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportSetting->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template') ?></th>
            <td><?= $reportSetting->has('report_template') ? $this->Html->link($reportSetting->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportSetting->report_template->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $reportSetting->has('grade') ? $this->Html->link($reportSetting->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportSetting->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= $reportSetting->has('course') ? $this->Html->link($reportSetting->course->name, ['controller' => 'Courses', 'action' => 'view', $reportSetting->course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportSetting->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportSetting->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportSetting->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Status') ?></th>
            <td><?= $reportSetting->course_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Comment Status') ?></th>
            <td><?= $reportSetting->course_comment_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand Status') ?></th>
            <td><?= $reportSetting->strand_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand Comment Status') ?></th>
            <td><?= $reportSetting->strand_comment_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Standard Status') ?></th>
            <td><?= $reportSetting->standard_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Standard Comment Status') ?></th>
            <td><?= $reportSetting->standard_comment_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Impact Status') ?></th>
            <td><?= $reportSetting->impact_status ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Impact Comment Status') ?></th>
            <td><?= $reportSetting->impact_comment_status ? __('Yes') : __('No'); ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

