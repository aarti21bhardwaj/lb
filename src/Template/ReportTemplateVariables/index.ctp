<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplateVariables index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Variables') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_type_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('identifier') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateVariables as $reportTemplateVariable): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateVariable->id) ?></td>
                                        <td><?= $reportTemplateVariable->has('report_template_type') ? $this->Html->link($reportTemplateVariable->report_template_type->id, ['controller' => 'ReportTemplateTypes', 'action' => 'view', $reportTemplateVariable->report_template_type->id]) : '' ?></td>
                                        <td><?= h($reportTemplateVariable->name) ?></td>
                                        <td><?= h($reportTemplateVariable->identifier) ?></td>
                                        <td><?= h($reportTemplateVariable->created) ?></td>
                                        <td><?= h($reportTemplateVariable->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplateVariable->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplateVariable->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplateVariable->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateVariable->id)]) ?>
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
