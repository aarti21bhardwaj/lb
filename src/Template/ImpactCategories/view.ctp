<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ImpactCategory $impactCategory
  */
?>
<!-- <div class="impactCategories view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($impactCategory->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($impactCategory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $this->Text->autoParagraph(h($impactCategory->description)); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($impactCategory->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($impactCategory->modified) ?></td>
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
        <?php if (!empty($impactCategory->impacts)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Impact Category Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($impactCategory->impacts as $impacts): ?>
            <tr>
                <td><?= h($impacts->id) ?></td>
                <td><?= h($impacts->name) ?></td>
                <td><?= h($impacts->description) ?></td>
                <td><?= h($impacts->impact_category_id) ?></td>
                <td><?= h($impacts->created) ?></td>
                <td><?= h($impacts->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Impacts', 'action' => 'view', $impacts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Impacts', 'action' => 'edit', $impacts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Impacts', 'action' => 'delete', $impacts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $impacts->id)]) ?>
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

