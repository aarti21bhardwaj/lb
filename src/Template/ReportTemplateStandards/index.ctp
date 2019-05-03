<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplateStandards index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Standards') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('standard_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateStandards as $reportTemplateStandard): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateStandard->id) ?></td>
                                        <td><?= $reportTemplateStandard->has('report_template') ? $this->Html->link($reportTemplateStandard->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateStandard->report_template->id]) : '' ?></td>
                                        <td><?= $reportTemplateStandard->has('standard') ? $this->Html->link($reportTemplateStandard->standard->name, ['controller' => 'Standards', 'action' => 'view', $reportTemplateStandard->standard->id]) : '' ?></td>
                                        <td><?= h($reportTemplateStandard->created) ?></td>
                                        <td><?= h($reportTemplateStandard->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplateStandard->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplateStandard->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplateStandard->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateStandard->id)]) ?>
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
