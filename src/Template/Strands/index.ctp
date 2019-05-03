<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="strands index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Strands') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('learning_area_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('code') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($strands as $strand): ?>
                        <tr>
                                        <td><?= $this->Number->format($strand->id) ?></td>
                                        <td><?= h($strand->name) ?></td>
                                        <td><?= $strand->has('learning_area') ? $this->Html->link($strand->learning_area->name, ['controller' => 'LearningAreas', 'action' => 'view', $strand->learning_area->id]) : '' ?></td>
                                        <td><?= h($strand->created) ?></td>
                                        <td><?= h($strand->modified) ?></td>
                                        <td><?= h($strand->code) ?></td>
                                        <td><?= $strand->has('grade') ? $this->Html->link($strand->grade->name, ['controller' => 'Grades', 'action' => 'view', $strand->grade->id]) : '' ?></td>
                                        <td class="actions">
                                            <?= '<a href='.$this->Url->build(['action' => 'view', $strand->id]).' class="btn btn-xs btn-success">' ?>
                                                <i class="fa fa-eye fa-fw"></i>
                                            </a>
                                            <?= '<a href='.$this->Url->build(['action' => 'edit', $strand->id]).' class="btn btn-xs btn-warning"">' ?>
                                            <i class="fa fa-pencil fa-fw"></i>
                                            </a>
                                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $strand->id], [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $strand->id),
                                        'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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
