<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reports index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Reports') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_page_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sort_order') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                        <tr>
                                        <td><?= $this->Number->format($report->id) ?></td>
                                        <td><?= $report->has('report_template') ? $this->Html->link($report->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $report->report_template->id]) : '' ?></td>
                                        <td><?= $report->has('grade') ? $this->Html->link($report->grade->name, ['controller' => 'Grades', 'action' => 'view', $report->grade->id]) : '' ?></td>
                                        <td><?= $report->has('report_page') ? $this->Html->link($report->report_page->title, ['controller' => 'ReportPages', 'action' => 'view', $report->report_page->id]) : '' ?></td>
                                        <td><?= $report->has('course') ? $this->Html->link($report->course->name, ['controller' => 'Courses', 'action' => 'view', $report->course->id]) : '' ?></td>
                                        <td><?= $this->Number->format($report->sort_order) ?></td>
                                        <td><?= h($report->created) ?></td>
                                        <td><?= h($report->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $report->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $report->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $report->id], ['confirm' => __('Are you sure you want to delete # {0}?', $report->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
        
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
