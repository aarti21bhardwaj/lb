<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="campusCourses index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Campus Courses') ?></h3>
            <div class="text-right">
                <?=$this->Html->link('Add Campus Courses', ['controller' => 'CampusCourses', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
            </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('campus_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campusCourses as $campusCourse): ?>
                        <tr>
                                        <td><?= $this->Number->format($campusCourse->id) ?></td>
                                        <td><?= $campusCourse->has('campus') ? $this->Html->link($campusCourse->campus->name, ['controller' => 'Campuses', 'action' => 'view', $campusCourse->campus->id]) : '' ?></td>
                                        <td><?= $campusCourse->has('course') ? $this->Html->link($campusCourse->course->name, ['controller' => 'Courses', 'action' => 'view', $campusCourse->course->id]) : '' ?></td>
                                        <td><?= h($campusCourse->created) ?></td>
                                        <td><?= h($campusCourse->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $campusCourse->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $campusCourse->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $campusCourse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campusCourse->id)]) ?>
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
