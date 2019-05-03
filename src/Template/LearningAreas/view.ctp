<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\LearningArea $learningArea
  */
?>
<!-- <div class="learningAreas view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($learningArea->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Curriculum') ?></th>
            <td><?= $learningArea->has('curriculum') ? $this->Html->link($learningArea->curriculum->name, ['controller' => 'Curriculums', 'action' => 'view', $learningArea->curriculum->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($learningArea->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($learningArea->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $this->Text->autoParagraph(h($learningArea->description)); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($learningArea->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($learningArea->modified) ?></td>
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
        <h4><?= __('Related Strands') ?></h4>
        </div>
        <?php if (!empty($learningArea->strands)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Learning Area Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($learningArea->strands as $strands): ?>
            <tr>
                <td><?= h($strands->id) ?></td>
                <td><?= h($strands->name) ?></td>
                <td><?= h($strands->description) ?></td>
                <td><?= h($strands->learning_area_id) ?></td>
                <td><?= h($strands->created) ?></td>
                <td><?= h($strands->modified) ?></td>
                <td class="actions">
                <?= '<a href='.$this->Url->build(['controller' => 'Strands','action' => 'view', $strands->id]).' class="btn btn-xs btn-success">' ?>
                    <i class="fa fa-eye fa-fw"></i>
                </a>
                <?= '<a href='.$this->Url->build(['controller' => 'Strands','action' => 'edit', $strands->id]).' class="btn btn-xs btn-warning"">' ?>
                    <i class="fa fa-pencil fa-fw"></i>
                </a>
                <?= $this->Form->postLink(__(''), ['controller' => 'Strands','action' => 'delete', $strands->id], ['confirm' => __('Are you sure you want to delete # {0}?', $strands->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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

