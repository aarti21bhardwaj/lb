<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\School $school
  */
?>
<!-- <div class="schools view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($school->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($school->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($school->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($school->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($school->modified) ?></td>
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
        <h4><?= __('Related Campuses') ?></h4>
        </div>
        <?php if (!empty($school->campuses)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('School Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($school->campuses as $campuses): ?>
            <tr>
                <td><?= h($campuses->id) ?></td>
                <td><?= h($campuses->name) ?></td>
                <td><?= h($campuses->school_id) ?></td>
                <td><?= h($campuses->created) ?></td>
                <td><?= h($campuses->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Campuses', 'action' => 'view', $campuses->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Campuses', 'action' => 'edit', $campuses->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Campuses', 'action' => 'delete', $campuses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campuses->id)]) ?>
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
        <h4><?= __('Related Divisions') ?></h4>
        </div>
        <?php if (!empty($school->divisions)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('School Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($school->divisions as $divisions): ?>
            <tr>
                <td><?= h($divisions->id) ?></td>
                <td><?= h($divisions->name) ?></td>
                <td><?= h($divisions->school_id) ?></td>
                <td><?= h($divisions->created) ?></td>
                <td><?= h($divisions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Divisions', 'action' => 'view', $divisions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Divisions', 'action' => 'edit', $divisions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Divisions', 'action' => 'delete', $divisions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $divisions->id)]) ?>
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
        <h4><?= __('Related School Users') ?></h4>
        </div>
        <?php if (!empty($school->school_users)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('School Id') ?></th>
                <th scope="col"><?= __('Legacy Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($school->school_users as $schoolUsers): ?>
            <tr>
                <td><?= h($schoolUsers->id) ?></td>
                <td><?= h($schoolUsers->user_id) ?></td>
                <td><?= h($schoolUsers->school_id) ?></td>
                <td><?= h($schoolUsers->legacy_id) ?></td>
                <td><?= h($schoolUsers->created) ?></td>
                <td><?= h($schoolUsers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SchoolUsers', 'action' => 'view', $schoolUsers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SchoolUsers', 'action' => 'edit', $schoolUsers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolUsers', 'action' => 'delete', $schoolUsers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolUsers->id)]) ?>
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

