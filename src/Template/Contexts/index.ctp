<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="contexts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Contexts') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contexts as $context): ?>
                        <tr>
                                        <td><?= $this->Number->format($context->id) ?></td>
                                        <td><?= h($context->name) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $context->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $context->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $context->id], ['confirm' => __('Are you sure you want to delete # {0}?', $context->id)]) ?>
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
