<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportingPeriods index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Reporting Periods') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('closing_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('term_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportingPeriods as $reportingPeriod): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportingPeriod->id) ?></td>
                                        <td><?= h($reportingPeriod->name) ?></td>
                                        <td><?= h($reportingPeriod->start_date) ?></td>
                                        <td><?= h($reportingPeriod->end_date) ?></td>
                                        <td><?= h($reportingPeriod->closing_date) ?></td>
                                        <td><?= $reportingPeriod->has('term') ? $this->Html->link($reportingPeriod->term->name, ['controller' => 'Terms', 'action' => 'view', $reportingPeriod->term->id]) : '' ?></td>
                                        <td><?= h($reportingPeriod->created) ?></td>
                                        <td><?= h($reportingPeriod->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportingPeriod->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportingPeriod->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportingPeriod->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportingPeriod->id)]) ?>
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
