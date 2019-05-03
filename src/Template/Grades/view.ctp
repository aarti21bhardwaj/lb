<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Grade $grade
  */
?>
<!-- <div class="grades view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($grade->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($grade->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($grade->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort Order') ?></th>
            <td><?= $this->Number->format($grade->sort_order) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($grade->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($grade->modified) ?></td>
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
        <h4><?= __('Related Course') ?></h4>
        </div>
        <?php if (!empty($grade->course)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($grade->course as $course): ?>
            <tr>
                <td><?= h($course->id) ?></td>
                <td><?= h($course->name) ?></td>
                <td><?= h($course->description) ?></td>
                <td><?= h($course->grade_id) ?></td>
                <td><?= h($course->created) ?></td>
                <td><?= h($course->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Course', 'action' => 'view', $course->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Course', 'action' => 'edit', $course->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Course', 'action' => 'delete', $course->id], ['confirm' => __('Are you sure you want to delete # {0}?', $course->id)]) ?>
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
        <h4><?= __('Related Division Grades') ?></h4>
        </div>
        <?php if (!empty($grade->division_grades)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Division Id') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($grade->division_grades as $divisionGrades): ?>
            <tr>
                <td><?= h($divisionGrades->id) ?></td>
                <td><?= h($divisionGrades->division_id) ?></td>
                <td><?= h($divisionGrades->grade_id) ?></td>
                <td><?= h($divisionGrades->created) ?></td>
                <td><?= h($divisionGrades->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'DivisionGrades', 'action' => 'view', $divisionGrades->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'DivisionGrades', 'action' => 'edit', $divisionGrades->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'DivisionGrades', 'action' => 'delete', $divisionGrades->id], ['confirm' => __('Are you sure you want to delete # {0}?', $divisionGrades->id)]) ?>
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
        <h4><?= __('Related Standards') ?></h4>
        </div>
        <?php if (!empty($grade->standards)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Strand Id') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($grade->standards as $standards): ?>
            <tr>
                <td><?= h($standards->id) ?></td>
                <td><?= h($standards->name) ?></td>
                <td><?= h($standards->description) ?></td>
                <td><?= h($standards->strand_id) ?></td>
                <td><?= h($standards->grade_id) ?></td>
                <td><?= h($standards->created) ?></td>
                <td><?= h($standards->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Standards', 'action' => 'view', $standards->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Standards', 'action' => 'edit', $standards->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Standards', 'action' => 'delete', $standards->id], ['confirm' => __('Are you sure you want to delete # {0}?', $standards->id)]) ?>
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

