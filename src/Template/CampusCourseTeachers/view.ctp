<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\CampusCourseTeacher $campusCourseTeacher
  */
?>
<!-- <div class="campusCourseTeachers view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($campusCourseTeacher->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Campus Course') ?></th>
            <td><?= $campusCourseTeacher->has('campus_course') ? $this->Html->link($campusCourseTeacher->campus_course->id, ['controller' => 'CampusCourses', 'action' => 'view', $campusCourseTeacher->campus_course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Teacher') ?></th>
            <td><?= $campusCourseTeacher->has('teacher') ? $this->Html->link($campusCourseTeacher->teacher->id, ['controller' => 'Users', 'action' => 'view', $campusCourseTeacher->teacher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($campusCourseTeacher->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Leader') ?></th>
            <td><?= $campusCourseTeacher->is_leader ? __('Yes') : __('No'); ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

