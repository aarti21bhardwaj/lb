<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\SchoolUser $schoolUser
  */
?>
<!-- <div class="schoolUsers view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($schoolUser->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $schoolUser->has('user') ? $this->Html->link($schoolUser->user->id, ['controller' => 'Users', 'action' => 'view', $schoolUser->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('School') ?></th>
            <td><?= $schoolUser->has('school') ? $this->Html->link($schoolUser->school->name, ['controller' => 'Schools', 'action' => 'view', $schoolUser->school->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Legacy Id') ?></th>
            <td><?= h($schoolUser->legacy_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($schoolUser->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($schoolUser->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($schoolUser->modified) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->


<!-- </div> -->

