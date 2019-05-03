<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Section $section
  */
?>
<!-- <div class="sections view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($section->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($section->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= h($section->course->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Teacher') ?></th>
            <td><?= h($section->teacher->first_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($section->id) ?></td>
        </tr>
       <!--  <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($section->created) ?></td>
        </tr> -->
        <!-- <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($section->modified) ?></td>
        </tr> -->
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Section Students') ?></h4>
        </div>
        <?php if (!empty($section->section_students)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Section Name') ?></th>
                <th scope="col"><?= __('Student Name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($section->section_students as $sectionStudents):?>
            <tr>
                <td><?= h($sectionStudents->id) ?></td>
                <td><?= h($sectionStudents->section->name) ?></td>
                <td><?= h($sectionStudents->student->first_name.' '.$sectionStudents->student->last_name) ?></td>
                <td class="actions">
                    <!-- <?= $this->Html->link(__('View'), ['controller' => 'SectionStudents', 'action' => 'view', $sectionStudents->id]) ?> -->
                    <!-- <?= $this->Html->link(__('Edit'), ['controller' => 'SectionStudents', 'action' => 'edit', $sectionStudents->id]) ?> -->
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SectionStudents', 'action' => 'delete', $sectionStudents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sectionStudents->id)]) ?>
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

