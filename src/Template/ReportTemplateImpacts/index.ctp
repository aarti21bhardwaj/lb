<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplateImpacts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Impacts') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('impact_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateImpacts as $reportTemplateImpact): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateImpact->id) ?></td>
                                        <td><?= $reportTemplateImpact->has('report_template') ? $this->Html->link($reportTemplateImpact->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateImpact->report_template->id]) : '' ?></td>
                                        <td><?= $this->Number->format($reportTemplateImpact->course_id) ?></td>
                                        <td><?= $reportTemplateImpact->has('impact') ? $this->Html->link($reportTemplateImpact->impact->name, ['controller' => 'Impacts', 'action' => 'view', $reportTemplateImpact->impact->id]) : '' ?></td>
                                        <td><?= h($reportTemplateImpact->created) ?></td>
                                        <td><?= h($reportTemplateImpact->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplateImpact->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplateImpact->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplateImpact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateImpact->id)]) ?>
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
