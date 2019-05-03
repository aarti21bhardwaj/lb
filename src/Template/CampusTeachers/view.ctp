<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\CampusTeacher $campusTeacher
  */
?>
<!-- <div class="campusTeachers view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($campusTeacher->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
            <table class="table">
                <tr>
                    <th scope="row"><?= __('Campus') ?></th>
                    <td><?= $campusTeacher->has('campus') ? $this->Html->link($campusTeacher->campus->name, ['controller' => 'Campuses', 'action' => 'view', $campusTeacher->campus->id]) : '' ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Teacher') ?></th>
                    <td><?= $campusTeacher->has('teacher') ? $this->Html->link($campusTeacher->teacher->first_name.' '.$campusTeacher->teacher->last_name, ['controller' => 'Users', 'action' => 'view', $campusTeacher->teacher->id]) : '' ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($campusTeacher->id) ?></td>
                </tr>
            </table> <!-- table end-->
        </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

