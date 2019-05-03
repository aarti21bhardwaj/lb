<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportingPeriod $reportingPeriod
  */
?>
<!-- <div class="reportingPeriods view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportingPeriod->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($reportingPeriod->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Term') ?></th>
            <td><?= $reportingPeriod->has('term') ? $this->Html->link($reportingPeriod->term->name, ['controller' => 'Terms', 'action' => 'view', $reportingPeriod->term->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportingPeriod->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($reportingPeriod->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($reportingPeriod->end_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Closing Date') ?></th>
            <td><?= h($reportingPeriod->closing_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportingPeriod->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportingPeriod->modified) ?></td>
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
        <h4><?= __('Related Report Templates') ?></h4>
        </div>
        <?php if (!empty($reportingPeriod->report_templates)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Academic Scale') ?></th>
                <th scope="col"><?= __('Impact Scale') ?></th>
                <th scope="col"><?= __('Reporting Period Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reportingPeriod->report_templates as $reportTemplates): ?>
            <tr>
                <td><?= h($reportTemplates->id) ?></td>
                <td><?= h($reportTemplates->academic_scale) ?></td>
                <td><?= h($reportTemplates->impact_scale) ?></td>
                <td><?= h($reportTemplates->reporting_period_id) ?></td>
                <td><?= h($reportTemplates->created) ?></td>
                <td><?= h($reportTemplates->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplates->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ReportTemplates', 'action' => 'edit', $reportTemplates->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ReportTemplates', 'action' => 'delete', $reportTemplates->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplates->id)]) ?>
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

