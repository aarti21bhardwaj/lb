<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportTemplate $reportTemplate
  */
?>
<!-- <div class="reportTemplates view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportTemplate->reporting_period->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        
       <!--  <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplate->id) ?></td>
        </tr> -->
        
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($reportTemplate->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Academic Scale') ?></th>
            <td><?= h($scale[$reportTemplate->academic_scale]) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Impact Scale') ?></th>
            <td><?= h($scale[$reportTemplate->impact_scale]) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reporting Period') ?></th>
            <td><?= h($reportTemplate->reporting_period->name) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Report Settings') ?></h4>
        </div>
        <?php if (!empty($reportTemplate->report_settings)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Report Template Id') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Course Id') ?></th>
                <th scope="col"><?= __('Course Status') ?></th>
                <th scope="col"><?= __('Course Comment Status') ?></th>
                <th scope="col"><?= __('Strand Status') ?></th>
                <th scope="col"><?= __('Strand Comment Status') ?></th>
                <th scope="col"><?= __('Standard Status') ?></th>
                <th scope="col"><?= __('Standard Comment Status') ?></th>
                <th scope="col"><?= __('Impact Status') ?></th>
                <th scope="col"><?= __('Impact Comment Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reportTemplate->report_settings as $reportSettings): ?>
            <tr>
                <td><?= h($reportSettings->id) ?></td>
                <td><?= h($reportSettings->report_template_id) ?></td>
                <td><?= h($reportSettings->grade_id) ?></td>
                <td><?= h($reportSettings->course_id) ?></td>
                <td><?= h($reportSettings->course_status) ?></td>
                <td><?= h($reportSettings->course_comment_status) ?></td>
                <td><?= h($reportSettings->strand_status) ?></td>
                <td><?= h($reportSettings->strand_comment_status) ?></td>
                <td><?= h($reportSettings->standard_status) ?></td>
                <td><?= h($reportSettings->standard_comment_status) ?></td>
                <td><?= h($reportSettings->impact_status) ?></td>
                <td><?= h($reportSettings->impact_comment_status) ?></td>
                <td><?= h($reportSettings->created) ?></td>
                <td><?= h($reportSettings->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ReportSettings', 'action' => 'view', $reportSettings->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ReportSettings', 'action' => 'edit', $reportSettings->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ReportSettings', 'action' => 'delete', $reportSettings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportSettings->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Reports') ?></h4>
        </div>
        <?php if (!empty($reportTemplate->reports)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Report Template Id') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Report Page Id') ?></th>
                <th scope="col"><?= __('Course Id') ?></th>
                <th scope="col"><?= __('Sort Order') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reportTemplate->reports as $reports): ?>
            <tr>
                <td><?= h($reports->id) ?></td>
                <td><?= h($reports->report_template_id) ?></td>
                <td><?= h($reports->grade_id) ?></td>
                <td><?= h($reports->report_page_id) ?></td>
                <td><?= h($reports->course_id) ?></td>
                <td><?= h($reports->sort_order) ?></td>
                <td><?= h($reports->created) ?></td>
                <td><?= h($reports->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Reports', 'action' => 'view', $reports->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Reports', 'action' => 'edit', $reports->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Reports', 'action' => 'delete', $reports->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reports->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Grades') ?></h4>
        </div>
        <?php if (!empty($reportTemplate->grades)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Sort Order') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reportTemplate->grades as $grades): ?>
            <tr>
                <td><?= h($grades->id) ?></td>
                <td><?= h($grades->name) ?></td>
                <td><?= h($grades->sort_order) ?></td>
                <td><?= h($grades->created) ?></td>
                <td><?= h($grades->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Grades', 'action' => 'view', $grades->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Grades', 'action' => 'edit', $grades->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Grades', 'action' => 'delete', $grades->id], ['confirm' => __('Are you sure you want to delete # {0}?', $grades->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->

<!-- </div> -->

