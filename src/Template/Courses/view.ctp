<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Course $course
  */
?>
<!-- <div class="courses view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($course->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($course->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $course->grade->name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Learning Area') ?></th>
            <td><?= $course->learning_area->name ?></td>
        </tr>
        <!-- <tr>
            <th scope="row"><?= __('Term') ?></th>
            <td><?= $course->term->name ?></td>
        </tr> -->
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($course->id) ?></td>
        </tr>
        <!-- <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($course->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($course->modified) ?></td>
        </tr> -->
         <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $this->Text->autoParagraph(h($course->description)); ?></td>
        </tr>
    </table> <!-- table end-->
    <!-- <div class="col-sm-2">
        <h4><?= __('Description') ?></h4>
    </div>
    <div class="col-sm-10">
        <?= $this->Text->autoParagraph(h($course->description)); ?>
    </div> -->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

<div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
    <?php if (!empty($course->campus_courses)): ?>
    <div class="ibox-title">
    <h4><?= __('Related Campus Course Teachers') ?></h4>
   <!--  <div class="text-right">
            <?=$this->Html->link('Add More Teachers', ['controller' => 'CampusCourseTeachers', 'action' => 'add', $course->campus_course],['class' => ['btn', 'btn-success']])?>
        </div>
    </div> -->
    <div class="ibox-content">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <th scope="col"><?= __('Id') ?></th>
            <th scope="col"><?= __('Campus') ?></th>
            <th scope="col"><?= __('Course') ?></th>
            <th scope="col"><?= __('Teachers') ?></th>
            <th scope="col"><?= __('Is Leader') ?></th>
            <!-- <th scope="col"><?= __('Created') ?></th> -->
            <!-- <th scope="col"><?= __('Modified') ?></th> -->

            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($campusCourseTeachers as $campusCourseTeacher):?>
        <tr>
            <td><?= h($campusCourseTeacher->id) ?></td>
            <td><?= h($campusCourseTeacher->campus_course->campus->name) ?></td>
            <td><?= h($campusCourseTeacher->campus_course->course->name) ?></td>
            <td><?= h($campusCourseTeacher->teacher->first_name) ?></td>
            <td><?= !empty($campusCourseTeacher->is_leader)? 'Yes' : 'No' ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'CampusCourseTeachers', 'action' => 'view', $campusCourseTeacher->id]) ?>
                <?= $this->Html->link(__('Edit'), ['controller' => 'CampusCourseTeachers', 'action' => 'edit', $campusCourseTeacher->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['controller' => 'CampusCourseTeachers', 'action' => 'delete', $campusCourseTeacher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campusCourseTeacher->id)]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div><!-- .ibox-content end -->
    <?php endif; ?>
    </div><!-- ibox end-->
</div><!-- .col-lg-12 end-->
</div><!-- .row end-->

<div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
    <?php if (!empty($course->campus_courses)): ?>
    <div class="ibox-title">
    <h4><?= __('Related Course Strands') ?></h4>
    <div class="text-right" style = 'margin-top :-26px'>
             <?=$this->Html->link('Manage Strands', ['controller' => 'CourseStrands', 'action' => 'add', $course->id],['class' => ['btn', 'btn-success']])?>
        </div>
    </div>
    <div class="ibox-content">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
             <th scope="col"><?= __('S.No') ?></th>
                <th scope="col"><?= __('Course') ?></th>
                <th scope="col"><?= __('Strands') ?></th>
                <th scope="col"><?= __('Grades') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
         <?php foreach ($course->course_strands as $key => $courseStrand):?>
            <tr>
                <td><?= $this->Number->format($key + 1) ?></td>
                <td><?= h($course->name) ?></td>
                <td><?= h($courseStrand->strand->name) ?></td>
                <td><?= h($courseStrand->grade->name) ?></td>
                <td class="actions">
                   <!--  <?= $this->Html->link(__('View'), ['controller' => 'CourseStrands', 'action' => 'view', $courseStrand->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CourseStrands', 'action' => 'edit', $courseStrand->id]) ?> -->
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CourseStrands', 'action' => 'delete', $courseStrand->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courseStrand->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
    </table>
    </div><!-- .ibox-content end -->
    <?php endif; ?>
    </div><!-- ibox end-->
</div><!-- .col-lg-12 end-->
</div><!-- .row end-->

<div class="row">
<div class="col-lg-12">
    <div class="ibox float-e-margins">
    <div class="ibox-title">
    <h4><?= __('Related Sections') ?></h4>
    </div>
    <?php if (!empty($course->sections)): ?>
    <div class="ibox-content">
    <table class="table" cellpadding="0" cellspacing="0">
        <tr>
            <th scope="col"><?= __('Id') ?></th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('Course Id') ?></th>
            <th scope="col"><?= __('Teacher Id') ?></th>
            <th scope="col"><?= __('Created') ?></th>
            <th scope="col"><?= __('Modified') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($course->sections as $sections): ?>
        <tr>
            <td><?= h($sections->id) ?></td>
            <td><?= h($sections->name) ?></td>
            <td><?= h($sections->course_id) ?></td>
            <td><?= h($sections->teacher_id) ?></td>
            <td><?= h($sections->created) ?></td>
            <td><?= h($sections->modified) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Sections', 'action' => 'view', $sections->id]) ?>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Sections', 'action' => 'edit', $sections->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sections', 'action' => 'delete', $sections->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sections->id)]) ?>
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

