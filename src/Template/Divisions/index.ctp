<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="divisions index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Divisions') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('school_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('campus_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($divisions as $division): ?>
                        <tr>
                                        <td><?= $this->Number->format($division->id) ?></td>
                                        <td><?= h($division->name) ?></td>
                                        <td><?= $division->has('school') ? $this->Html->link($division->school->name, ['controller' => 'Schools', 'action' => 'view', $division->school->id]) : '' ?></td>
                                        <td><?= $division->has('campus') ? $this->Html->link($division->campus->name, ['controller' => 'Campuses', 'action' => 'view', $division->campus->id]) : '' ?></td>
                                        <td><?= h($division->created) ?></td>
                                        <td><?= h($division->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $division->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $division->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $division->id], ['confirm' => __('Are you sure you want to delete # {0}?', $division->id)]) ?>
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
