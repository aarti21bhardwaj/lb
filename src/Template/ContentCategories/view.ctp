<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ContentCategory $contentCategory
  */
?>
<!-- <div class="contentCategories view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($contentCategory->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($contentCategory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($contentCategory->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($contentCategory->id) ?></td>
        </tr>
    </table> <!-- table end-->
    <div class="col-sm-2">
        <h4><?= __('Meta') ?></h4>
    </div>
   <!--  <div class="col-sm-10">
        <?= $this->Text->autoParagraph(h($contentCategory->meta)); ?>
    </div> -->
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
        <?php if (!empty($contentCategory->content_values)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <!-- <th scope="col"><?= __('Content Category Id') ?></th> -->
                <th scope="col"><?= __('Text') ?></th>
                <th scope="col"><?= __('Is Selectable') ?></th>
                <th scope="col"><?= __('Parent') ?></th>
                <!-- <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th> -->
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentCategory->content_values as $contentValues): ?>
            <tr>
                <td><?= h($contentValues->id) ?></td>
                <!-- <td><?= h($contentValues->content_category_id) ?></td> -->
                <td><?= h($contentValues->text) ?></td>
                <td><?= !empty($contentValues->is_selectable) ? 'True' : 'False' ?></td>
                <td><?= empty($contentValues->parent_id) ? '-' : h($contentValues->parent_id) ?></td>
                <!-- <td><?= h($contentValues->lft) ?></td>
                <td><?= h($contentValues->rght) ?></td>
                <td><?= h($contentValues->created) ?></td>
                <td><?= h($contentValues->modified) ?></td> -->
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ContentValues', 'action' => 'view', $contentValues->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ContentValues', 'action' => 'edit', $contentValues->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ContentValues', 'action' => 'delete', $contentValues->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contentValues->id)]) ?>
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
        <h4><?= __('Related Course Content Categories') ?></h4>
        </div>
        <?php if (!empty($contentCategory->course_content_categories)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Course Name') ?></th>
                <!-- <th scope="col"><?= __('Content Category Id') ?></th> -->
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentCategory->course_content_categories as $courseContentCategories): ?>
            <tr>
                <td><?= h($courseContentCategories->id) ?></td>
                <td><?= h($courseContentCategories->course->name) ?></td>
                <!-- <td><?= h($courseContentCategories->content_category_id) ?></td> -->
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CourseContentCategories', 'action' => 'view', $courseContentCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CourseContentCategories', 'action' => 'edit', $courseContentCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CourseContentCategories', 'action' => 'delete', $courseContentCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courseContentCategories->id)]) ?>
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
        <?php if (!empty($contentCategory->unit_contents)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <!-- <th scope="col"><?= __('Content Category Id') ?></th> -->
                <!-- <th scope="col"><?= __('Content Value') ?></th> -->
                <th scope="col"><?= __('Unit Name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentCategory->unit_contents as $unitContents): ?>
            <tr>
                <td><?= h($unitContents->id) ?></td>
                <!-- <td><?= h($unitContents->content_category_id) ?></td> -->
                <!-- <td><?= h($unitContents->content_value_id) ?></td> -->
                <td><?= h($unitContents->unit->name) ?></td>
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
    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Unit Specific Contents') ?></h4>
        </div>
        <?php if (!empty($contentCategory->unit_specific_contents)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Unit Name') ?></th>
                <!-- <th scope="col"><?= __('Content Category Id') ?></th> -->
                <th scope="col"><?= __('Text') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($contentCategory->unit_specific_contents as $unitSpecificContents): ?>
            <tr>
                <td><?= h($unitSpecificContents->id) ?></td>
                <td><?= h($unitSpecificContents->unit->name) ?></td>
                <!-- <td><?= h($unitSpecificContents->content_category_id) ?></td> -->  
                <td><?= h($unitSpecificContents->text) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'UnitSpecificContents', 'action' => 'view', $unitSpecificContents->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UnitSpecificContents', 'action' => 'edit', $unitSpecificContents->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UnitSpecificContents', 'action' => 'delete', $unitSpecificContents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $unitSpecificContents->id)]) ?>
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

