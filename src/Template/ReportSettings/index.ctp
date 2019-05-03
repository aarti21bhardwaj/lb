<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportSettings index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Settings') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_comment_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('strand_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('strand_comment_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('standard_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('standard_comment_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('impact_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('impact_comment_status') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportSettings as $reportSetting): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportSetting->id) ?></td>
                                        <td><?= $reportSetting->has('report_template') ? $this->Html->link($reportSetting->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportSetting->report_template->id]) : '' ?></td>
                                        <td><?= $reportSetting->has('grade') ? $this->Html->link($reportSetting->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportSetting->grade->id]) : '' ?></td>
                                        <td><?= $reportSetting->has('course') ? $this->Html->link($reportSetting->course->name, ['controller' => 'Courses', 'action' => 'view', $reportSetting->course->id]) : '' ?></td>
                                        <td><?= h($reportSetting->course_status) ?></td>
                                        <td><?= h($reportSetting->course_comment_status) ?></td>
                                        <td><?= h($reportSetting->strand_status) ?></td>
                                        <td><?= h($reportSetting->strand_comment_status) ?></td>
                                        <td><?= h($reportSetting->standard_status) ?></td>
                                        <td><?= h($reportSetting->standard_comment_status) ?></td>
                                        <td><?= h($reportSetting->impact_status) ?></td>
                                        <td><?= h($reportSetting->impact_comment_status) ?></td>
                                        <td><?= h($reportSetting->created) ?></td>
                                        <td><?= h($reportSetting->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportSetting->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportSetting->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportSetting->id)]) ?>
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
