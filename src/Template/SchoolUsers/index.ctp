<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="schoolUsers index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('School Users') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('school_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('legacy_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolUsers as $schoolUser): ?>
                        <tr>
                                        <td><?= $this->Number->format($schoolUser->id) ?></td>
                                        <td><?= $schoolUser->has('user') ? $this->Html->link($schoolUser->user->id, ['controller' => 'Users', 'action' => 'view', $schoolUser->user->id]) : '' ?></td>
                                        <td><?= $schoolUser->has('school') ? $this->Html->link($schoolUser->school->name, ['controller' => 'Schools', 'action' => 'view', $schoolUser->school->id]) : '' ?></td>
                                        <td><?= h($schoolUser->legacy_id) ?></td>
                                        <td><?= h($schoolUser->created) ?></td>
                                        <td><?= h($schoolUser->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $schoolUser->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $schoolUser->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $schoolUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolUser->id)]) ?>
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
