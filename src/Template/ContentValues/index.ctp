<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="contentValues index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Content Values') ?></h3>
            <div class="text-right">
                <?=$this->Html->link('Add Values', ['controller' => 'ContentValues', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
            </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>            
                                        <th scope="col">No.</th>
                                        <th scope="col">Content Category</th>
                                        <th scope="col">Text</th>
                                        <th scope="col">Is Selectable</th>
                                        <th scope="col">Parent</th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contentValues as $key => $contentValue): ?>
                        <tr>
                                        <td><?= $this->Number->format($key + 1) ?></td>
                                        <td><?= h($contentValue->content_category->name) ?></td>
                                        <td><?= h($contentValue->text) ?></td>
                                        <td><?= !empty($contentValue->is_selectable) ? h($contentValue->is_selectable) : '-' ?></td>
                                        <td><?= !empty($contentValue->parent_content_value->id) ? h($contentValue->parent_content_value->id) : '-'?></td>
                                        <!-- <td><?= h($contentValue->created) ?></td>
                                        <td><?= h($contentValue->modified) ?></td> -->
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $contentValue->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contentValue->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contentValue->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentValue->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div> -->
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
