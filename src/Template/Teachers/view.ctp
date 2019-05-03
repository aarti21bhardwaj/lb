<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Standard $standard
  */
?>
<!-- <div class="standards view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($standard->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($standard->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Strand') ?></th>
            <td><?= $standard->has('strand') ? $this->Html->link($standard->strand->name, ['controller' => 'Strands', 'action' => 'view', $standard->strand->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $standard->has('grade') ? $this->Html->link($standard->grade->name, ['controller' => 'Grades', 'action' => 'view', $standard->grade->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($standard->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($standard->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($standard->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    <div class="col-sm-2">
        <h4><?= __('Description') ?></h4>
    </div>
    <div class="col-sm-10">
        <?= $this->Text->autoParagraph(h($standard->description)); ?>
    </div>
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

