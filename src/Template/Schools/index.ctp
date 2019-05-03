<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="schools index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Schools') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schools as $school): ?>
                        <tr>
                                        <td><?= $this->Number->format($school->id) ?></td>
                                        <td><?= h($school->name) ?></td>
                                        <td><?= h($school->created) ?></td>
                                        <td><?= h($school->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $school->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $school->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $school->id], ['confirm' => __('Are you sure you want to delete # {0}?', $school->id)]) ?>
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
