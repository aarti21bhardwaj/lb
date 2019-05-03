<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="campusTeachers index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Campus Teachers') ?></h3>
            <div class="text-right">
                <?=$this->Html->link('Add Campus Teachers', ['controller' => 'CampusTeachers', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
            </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class = 'table table-striped table-bordered table-hover dataTables' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col">Id </th>
                                        <th scope="col">Campus </th>
                                        <th scope="col">Teacher </th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campusTeachers as $campusTeacher): ?>
                        <tr>
                                        <td><?= $this->Number->format($campusTeacher->id) ?></td>
                                        <td><?= $campusTeacher->has('campus') ? $this->Html->link($campusTeacher->campus->name, ['controller' => 'Campuses', 'action' => 'view', $campusTeacher->campus->id]) : '' ?></td>
                                        <td><?= $campusTeacher->has('teacher') ? $this->Html->link($campusTeacher->teacher->first_name.' '.$campusTeacher->teacher->last_name, ['controller' => 'Users', 'action' => 'view', $campusTeacher->teacher->id]) : '' ?></td>
                                        <td class="actions">
<!--                                 <?= $this->Html->link(__('View'), ['action' => 'view', $campusTeacher->id]) ?> -->
                                <!-- <?= $this->Html->link(__('Edit'), ['action' => 'edit', $campusTeacher->id]) ?> -->
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $campusTeacher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campusTeacher->id)]) ?>
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
