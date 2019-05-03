<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\SectionStudent $sectionStudent
  */
?>
<!-- <div class="sectionStudents view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($sectionStudent->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Section') ?></th>
            <td><?= $sectionStudent->has('section') ? $this->Html->link($sectionStudent->section->name, ['controller' => 'Sections', 'action' => 'view', $sectionStudent->section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sectionStudent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Student Id') ?></th>
            <td><?= $this->Number->format($sectionStudent->student_id) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

