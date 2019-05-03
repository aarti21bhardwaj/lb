<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Division $division
  */
?>
<!-- <div class="divisions view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($division->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($division->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('School') ?></th>
            <td><?= $division->has('school') ? $this->Html->link($division->school->name, ['controller' => 'Schools', 'action' => 'view', $division->school->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Campus') ?></th>
            <td><?= $division->has('campus') ? $this->Html->link($division->campus->name, ['controller' => 'Campuses', 'action' => 'view', $division->campus->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($division->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($division->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($division->modified) ?></td>
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
        <h4><?= __('Related Division Grades') ?></h4>
        </div>
        <?php if (!empty($division->division_grades)): ?>
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
            <?php foreach ($division->division_grades as $divisionGrades): ?>
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
        <h2><?= __('Terms') ?><?= '<a href='.$this->Url->build(['controller' => 'Terms','action' => 'add', $division->id]).' class="btn btn-info pull-right" title="Add Terms"><i class="fa fa-plus-square-o fa-fw"></i>Add Term</a>' ?></h2>
        </div>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('No.') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Start Date') ?></th>
                <th scope="col"><?= __('End Date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
           <?php foreach ($division->terms as $key => $term): ?>
             <tr>
                <td><?= h($key+1) ?></td>
                <td><?= h($term->name) ?></td>
                <td><?= h(($term->start_date)) ?></td>
                <td><?= h(($term->end_date)) ?></td>
                <td class="actions">
                    <?= '<a href='.$this->Url->build(['controller' => 'Terms','action' => 'view', $term->id]).' class="btn btn-xs btn-success">' ?>
                        <i class="fa fa-eye fa-fw"></i>
                    </a>
                    <?= '<a href='.$this->Url->build(['controller' => 'Terms','action' => 'edit', $term->id]).' class="btn btn-xs btn-warning"">' ?>
                        <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <?= $this->Form->postLink(__(''), ['controller' => 'Terms','action' => 'delete', $term->id], ['confirm' => __('Are you sure you want to delete # {0}?', $term->name), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                </td>
            </tr> 
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
