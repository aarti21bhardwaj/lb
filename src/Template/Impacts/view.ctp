<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Impact $impact
  */
?>
<!-- <div class="impacts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($impact->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($impact->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($impact->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Impact Category') ?></th>
            <td><?= $impact->has('impact_category') ? $this->Html->link($impact->impact_category->name, ['controller' => 'ImpactCategories', 'action' => 'view', $impact->impact_category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Impact') ?></th>
            <td><?= $impact->has('parent_impact') ? $this->Html->link($impact->parent_impact->name, ['controller' => 'Impacts', 'action' => 'view', $impact->parent_impact->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($impact->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($impact->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($impact->rght) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($impact->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($impact->modified) ?></td>
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
        <h4><?= __('Related Impacts') ?></h4>
        </div>
        <?php if (!empty($impact->child_impacts)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Impact Category Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($impact->child_impacts as $childImpacts): ?>
            <tr>
                <td><?= h($childImpacts->id) ?></td>
                <td><?= h($childImpacts->name) ?></td>
                <td><?= h($childImpacts->description) ?></td>
                <td><?= h($childImpacts->impact_category_id) ?></td>
                <td><?= h($childImpacts->created) ?></td>
                <td><?= h($childImpacts->modified) ?></td>
                <td><?= h($childImpacts->parent_id) ?></td>
                <td><?= h($childImpacts->lft) ?></td>
                <td><?= h($childImpacts->rght) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Impacts', 'action' => 'view', $childImpacts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Impacts', 'action' => 'edit', $childImpacts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Impacts', 'action' => 'delete', $childImpacts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childImpacts->id)]) ?>
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

