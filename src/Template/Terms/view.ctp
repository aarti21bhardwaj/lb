<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Term $term
  */
// pr($term); die;
?>
<!-- <div class="terms view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($term->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($term->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Academic Year') ?></th>
            <td><?= $term->has('academic_year') ? $this->Html->link($term->academic_year->name, ['controller' => 'AcademicYears', 'action' => 'view', $term->academic_year->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Division') ?></th>
            <td><?= $term->has('division') ? $this->Html->link($term->division->name, ['controller' => 'Divisions', 'action' => 'view', $term->division->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($term->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($term->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($term->end_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($term->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($term->modified) ?></td>
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
        <h4><?= __('Related Reporting Periods') ?></h4>
        </div>
        <?php if (!empty($term->reporting_periods)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <!-- <th scope="col"><?= __('Id') ?></th> -->
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Start Date') ?></th>
                <th scope="col"><?= __('End Date') ?></th>
                <th scope="col"><?= __('Closing Date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($term->reporting_periods as $reportingPeriod): ?>
            <tr>
                <!-- <td><?= h($courses->id) ?></td> -->
                <td><?= h($reportingPeriod->name) ?></td>
                <td><?= h($reportingPeriod->start_date->format('d-m-y')) ?></td>
                <td><?= h($reportingPeriod->end_date->format('d-m-y')) ?></td>
                <td><?= h($reportingPeriod->closing_date->format('d-m-y')) ?></td>
                <td class="actions">
                    <?= '<a href='.$this->Url->build(['controller' => 'ReportingPeriods','action' => 'edit', $reportingPeriod->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <?= $this->Form->postLink(__(''), ['controller' => 'ReportingPeriods','action' => 'delete', $reportingPeriod->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportingPeriod->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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

