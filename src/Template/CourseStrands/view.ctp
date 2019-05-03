<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\CourseStrand $courseStrand
  */
?>
<!-- <div class="courseStrands view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($courseStrand->course->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= h($courseStrand->course->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand') ?></th>
            <td><?= h($courseStrand->strand->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $courseStrand->grade->name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($courseStrand->id) ?></td>
        </tr>
        <!-- <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($courseStrand->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($courseStrand->modified) ?></td>
        </tr> -->
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

