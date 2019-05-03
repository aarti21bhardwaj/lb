<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="campusCourseTeachers index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Campus Course Teachers') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('campus_course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('teacher_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('is_leader') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campusCourseTeachers as $campusCourseTeacher): ?>
                        <tr>
                                        <td><?= $this->Number->format($campusCourseTeacher->id) ?></td>
                                        <td><?= $campusCourseTeacher->has('campus_course') ? $this->Html->link($campusCourseTeacher->campus_course->id, ['controller' => 'CampusCourses', 'action' => 'view', $campusCourseTeacher->campus_course->id]) : '' ?></td>
                                        <td><?= $campusCourseTeacher->has('teacher') ? $this->Html->link($campusCourseTeacher->teacher->id, ['controller' => 'Users', 'action' => 'view', $campusCourseTeacher->teacher->id]) : '' ?></td>
                                        <td><?= h($campusCourseTeacher->is_leader) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $campusCourseTeacher->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $campusCourseTeacher->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $campusCourseTeacher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campusCourseTeacher->id)]) ?>
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
