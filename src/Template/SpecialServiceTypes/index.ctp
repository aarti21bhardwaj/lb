<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="specialServiceTypes index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Special Service Types') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class = 'table table-striped table-bordered table-hover dataTables' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($specialServiceTypes as $specialServiceType): ?>
                        <tr>
                                        <td><?= $this->Number->format($specialServiceType->id) ?></td>
                                        <td><?= h($specialServiceType->name) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $specialServiceType->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $specialServiceType->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $specialServiceType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $specialServiceType->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
