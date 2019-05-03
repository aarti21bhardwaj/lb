<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\CampusCourse $campusCourse
  */
?>
<!-- <div class="campusCourses view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($campusCourse->course->name." at ".$campusCourse->campus->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Campus') ?></th>
            <td><?= $campusCourse->has('campus') ? $this->Html->link($campusCourse->campus->name, ['controller' => 'Campuses', 'action' => 'view', $campusCourse->campus->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= $campusCourse->has('course') ? $this->Html->link($campusCourse->course->name, ['controller' => 'Courses', 'action' => 'view', $campusCourse->course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($campusCourse->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($campusCourse->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($campusCourse->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Campus Course Teachers') ?></h4>
        <div class="text-right">
                <?=$this->Html->link('Add Campus Course Teacher', ['controller' => 'CampusCourseTeachers', 'action' => 'add', $campusCourse->id],['class' => ['btn', 'btn-success']])?>
            </div>
        </div>
        <?php if (!empty($campusCourse->campus_course_teachers)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Teacher') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($campusCourse->campus_course_teachers as $key=>$campusCourseTeachers): ?>
            <tr>
                <td><?= h($key+1) ?></td>
                <td><?= h($campusCourseTeachers->teacher->first_name." ".$campusCourseTeachers->teacher->last_name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CampusCourseTeachers', 'action' => 'view', $campusCourseTeachers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CampusCourseTeachers', 'action' => 'edit', $campusCourseTeachers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CampusCourseTeachers', 'action' => 'delete', $campusCourseTeachers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campusCourseTeachers->id)]) ?>
                    <!-- <?= '<a href='.$this->Url->build(['controller'=> 'CampusCourseTeachers','action' => 'add', $campusCourseTeachers->id]).' class="btn btn-xs btn-info" title="Add More Teachers"><i class="fa fa-rocket fa-fw"></i></a>' ?> -->
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->

<!-- </div> -->

