<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="divisionGrades index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Division Grades') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('division_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($divisionGrades as $divisionGrade): ?>
                        <tr>
                                        <td><?= $this->Number->format($divisionGrade->id) ?></td>
                                        <td><?= $divisionGrade->has('division') ? $this->Html->link($divisionGrade->division->name, ['controller' => 'Divisions', 'action' => 'view', $divisionGrade->division->id]) : '' ?></td>
                                        <td><?= $divisionGrade->has('grade') ? $this->Html->link($divisionGrade->grade->name, ['controller' => 'Grades', 'action' => 'view', $divisionGrade->grade->id]) : '' ?></td>
                                        <td><?= h($divisionGrade->created) ?></td>
                                        <td><?= h($divisionGrade->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $divisionGrade->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $divisionGrade->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $divisionGrade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $divisionGrade->id)]) ?>
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
