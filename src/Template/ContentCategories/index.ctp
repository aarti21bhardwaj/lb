<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="contentCategories index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Content Categories') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Content Category Name</th>
                                        <th scope="col">Content Category Type</th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contentCategories as $key => $contentCategory): ?>
                        <tr>
                                        <td><?= $this->Number->format($key + 1) ?></td>
                                        <td><?= h($contentCategory->name) ?></td>
                                        <td><?= h($contentCategory->type) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $contentCategory->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contentCategory->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contentCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentCategory->id)]) ?>
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
