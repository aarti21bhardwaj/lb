<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="grades index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Grades') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sort_order') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                        <tr>
                                        <td><?= $this->Number->format($grade->id) ?></td>
                                        <td><?= h($grade->name) ?></td>
                                        <td><?= $this->Number->format($grade->sort_order) ?></td>
                                        <td><?= h($grade->created) ?></td>
                                        <td><?= h($grade->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $grade->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $grade->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $grade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $grade->id)]) ?>
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
