<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplateGrades index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Grades') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateGrades as $reportTemplateGrade): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateGrade->id) ?></td>
                                        <td><?= $reportTemplateGrade->has('report_template') ? $this->Html->link($reportTemplateGrade->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateGrade->report_template->id]) : '' ?></td>
                                        <td><?= $reportTemplateGrade->has('grade') ? $this->Html->link($reportTemplateGrade->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportTemplateGrade->grade->id]) : '' ?></td>
                                        <td><?= h($reportTemplateGrade->created) ?></td>
                                        <td><?= h($reportTemplateGrade->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplateGrade->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplateGrade->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplateGrade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateGrade->id)]) ?>
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
