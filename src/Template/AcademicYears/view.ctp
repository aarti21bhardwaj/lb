<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\AcademicYear $academicYear
  */
?>
<!-- <div class="academicYears view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($academicYear->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($academicYear->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($academicYear->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($academicYear->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($academicYear->end_date) ?></td>
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
        <h4><?= __('Related Terms') ?></h4>
        </div>
        <?php if (!empty($academicYear->terms)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Start Date') ?></th>
                <th scope="col"><?= __('End Date') ?></th>
                <th scope="col"><?= __('Academic Year Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($academicYear->terms as $terms): ?>
            <tr>
                <td><?= h($terms->id) ?></td>
                <td><?= h($terms->name) ?></td>
                <td><?= h(($terms->start_date)->format('20y-m-d')) ?></td>
                <td><?= h(($terms->end_date)->format('20y-m-d')) ?></td>
                <td><?= h($terms->academic_year_id) ?></td>
                <td class="actions">
                <?= '<a href='.$this->Url->build(['controller' => 'Terms','action' => 'view', $terms->id]).' class="btn btn-xs btn-success">' ?>
                    <i class="fa fa-eye fa-fw"></i>
                </a>
                <?= '<a href='.$this->Url->build(['controller' => 'Terms','action' => 'edit', $terms->id]).' class="btn btn-xs btn-warning"">' ?>
                    <i class="fa fa-pencil fa-fw"></i>
                </a>
                <?= $this->Form->postLink(__(''), ['controller' => 'Terms','action' => 'delete', $terms->id], ['confirm' => __('Are you sure you want to delete # {0}?', $terms->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                </a>
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

