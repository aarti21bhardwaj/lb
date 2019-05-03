<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Strand $strand
  */
?>
<!-- <div class="strands view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($strand->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($strand->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Learning Area') ?></th>
            <td><?= $strand->has('learning_area') ? $this->Html->link($strand->learning_area->name, ['controller' => 'LearningAreas', 'action' => 'view', $strand->learning_area->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Code') ?></th>
            <td><?= h($strand->code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $strand->has('grade') ? $this->Html->link($strand->grade->name, ['controller' => 'Grades', 'action' => 'view', $strand->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $this->Text->autoParagraph(h($strand->description)); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($strand->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($strand->modified) ?></td>
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
        <h4><?= __('Related Standards') ?></h4>
        </div>
        <?php if (!empty($strand->standards)): ?>
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
                <th scope="col"><?= __('Code') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($strand->standards as $standards): ?>
            <tr>
                <td><?= h($standards->id) ?></td>
                <td><?= h($standards->name) ?></td>
                <td><?= h($standards->description) ?></td>
                <td><?= h($standards->strand_id) ?></td>
                <td><?= h($standards->grade_id) ?></td>
                <td><?= h($standards->created) ?></td>
                <td><?= h($standards->modified) ?></td>
                <td><?= h($standards->code) ?></td>
                <td><?= h($standards->parent_id) ?></td>
                <td><?= h($standards->lft) ?></td>
                <td><?= h($standards->rght) ?></td>
                <td class="actions">
                        <?= '<a href='.$this->Url->build(['controller' => 'Standards','action' => 'view', $standards->id]).' class="btn btn-xs btn-success">' ?>
                            <i class="fa fa-eye fa-fw"></i>
                        </a>
                        <?= '<a href='.$this->Url->build(['controller' => 'Standards','action' => 'edit', $standards->id]).' class="btn btn-xs btn-warning"">' ?>
                        <i class="fa fa-pencil fa-fw"></i>
                        </a>
                        <?= $this->Form->postLink(__(''), ['controller' => 'Standards','action' => 'delete', $standards->id], [
                    'confirm' => __('Are you sure you want to delete # {0}?', $standards->id),
                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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

