<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="impacts index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Impacts') ?></h3>
            <div class="text-right">
                <?=$this->Html->link('Add Impact', ['controller' => 'Impacts', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
            </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('description') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('impact_category_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('parent_id') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($impacts as $impact): ?>
                        <tr>
                                        <td><?= $this->Number->format($impact->id) ?></td>
                                        <td><?= h($impact->name) ?></td>
                                        <td><?= h($impact->description) ?></td>
                                        <td><?= $impact->has('impact_category') ? $this->Html->link($impact->impact_category->name, ['controller' => 'ImpactCategories', 'action' => 'view', $impact->impact_category->id]) : '' ?></td>
                                        <td><?= h($impact->created) ?></td>
                                        <td><?= h($impact->modified) ?></td>
                                        <td><?= $impact->has('parent_impact') ? $this->Html->link($impact->parent_impact->name, ['controller' => 'Impacts', 'action' => 'view', $impact->parent_impact->id]) : '' ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $impact->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $impact->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $impact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $impact->id)]) ?>
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
