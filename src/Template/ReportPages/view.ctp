<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ReportPage $reportPage
  */
?>
<!-- <div class="reportPages view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($reportPage->title) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($reportPage->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportPage->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reportPage->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reportPage->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    <div class="col-sm-2">
        <h4><?= __('Body') ?></h4>
    </div>
    <div class="col-sm-10">
        <?= $this->Text->autoParagraph(h($reportPage->body)); ?>
    </div>
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Reports') ?></h4>
        </div>
        <?php if (!empty($reportPage->reports)): ?>
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
            <?php foreach ($reportPage->reports as $reports): ?>
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

<!-- </div> -->

