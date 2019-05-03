<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ContentValue $contentValue
  */
?>
<!-- <div class="contentValues view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($contentValue->id) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Content Category') ?></th>
            <td><?= $contentValue->has('content_category') ? $this->Html->link($contentValue->content_category->name, ['controller' => 'ContentCategories', 'action' => 'view', $contentValue->content_category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Text') ?></th>
            <td><?= h($contentValue->text) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Content Value') ?></th>
            <td><?= $contentValue->has('parent_content_value') ? $this->Html->link($contentValue->parent_content_value->id, ['controller' => 'ContentValues', 'action' => 'view', $contentValue->parent_content_value->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($contentValue->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($contentValue->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($contentValue->rght) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($contentValue->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($contentValue->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Selectable') ?></th>
            <td><?= $contentValue->is_selectable ? __('Yes') : __('No'); ?></td>
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
        <h4><?= __('Related Content Values') ?></h4>
        </div>
        <?php if (!empty($contentValue->child_content_values)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Content Category Id') ?></th>
                <th scope="col"><?= __('Text') ?></th>
                <th scope="col"><?= __('Is Selectable') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentValue->child_content_values as $childContentValues): ?>
            <tr>
                <td><?= h($childContentValues->id) ?></td>
                <td><?= h($childContentValues->content_category_id) ?></td>
                <td><?= h($childContentValues->text) ?></td>
                <td><?= h($childContentValues->is_selectable) ?></td>
                <td><?= h($childContentValues->parent_id) ?></td>
                <td><?= h($childContentValues->lft) ?></td>
                <td><?= h($childContentValues->rght) ?></td>
                <td><?= h($childContentValues->created) ?></td>
                <td><?= h($childContentValues->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ContentValues', 'action' => 'view', $childContentValues->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ContentValues', 'action' => 'edit', $childContentValues->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ContentValues', 'action' => 'delete', $childContentValues->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childContentValues->id)]) ?>
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
        <h4><?= __('Related Unit Contents') ?></h4>
        </div>
        <?php if (!empty($contentValue->unit_contents)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Content Category Id') ?></th>
                <th scope="col"><?= __('Content Value Id') ?></th>
                <th scope="col"><?= __('Unit Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentValue->unit_contents as $unitContents): ?>
            <tr>
                <td><?= h($unitContents->id) ?></td>
                <td><?= h($unitContents->content_category_id) ?></td>
                <td><?= h($unitContents->content_value_id) ?></td>
                <td><?= h($unitContents->unit_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UnitContents', 'action' => 'view', $unitContents->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UnitContents', 'action' => 'edit', $unitContents->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UnitContents', 'action' => 'delete', $unitContents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $unitContents->id)]) ?>
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

