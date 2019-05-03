<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplates index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Templates') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class = 'table table-striped table-bordered table-hover dataTables' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('academic_scale') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('impact_scale') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('reporting_period_id') ?></th>
                                       <!--  <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th> -->
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplates as $reportTemplate): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplate->id) ?></td>
                                        <td><?= h($reportTemplate->name) ?></td>
                                        <td><?= h($scales[$reportTemplate->academic_scale]) ?></td>
                                        <td><?= h($scales[$reportTemplate->impact_scale]) ?></td>
                                        <td><?= h($reportTemplate->reporting_period->name)?></td>
                                        <!-- <td class="actions">
                                        <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplate->id]) ?>
                                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplate->id]) ?>
                                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplate->id)]) ?>
                                        </td> -->
                                        <td class="actions">
                                            <?= '<a href='.$this->Url->build(['action' => 'view', $reportTemplate->id]).' class="btn btn-xs btn-success">' ?>
                                                <i class="fa fa-eye fa-fw"></i>
                                            </a>
                                            <?= '<a href='.$this->Url->build(['action' => 'edit', $reportTemplate->id]).' class="btn btn-xs btn-warning"">' ?>
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </a>
                                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $reportTemplate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplate->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                            <?= '<a href='.$this->Url->build(['controller'=> 'ReportSettings','action' => 'settings', $reportTemplate->id]).' class="btn btn-xs btn-primary" title="Configure Grade And Course Setting"><i class="fa fa-gears fa-fw"></i></a>' ?>
                                            </a>
                                            <?= '<a href='.$this->Url->build(['controller'=> 'ReportTemplates','action' => 'studentReports', $reportTemplate->id]).' class="btn btn-xs btn-success" title="Student Reports"><i class="fa fa-list-ul"></i></a>' ?>
                                            </a>
                                        </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
