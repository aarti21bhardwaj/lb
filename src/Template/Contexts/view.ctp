<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Context $context
  */
?>
<!-- <div class="contexts view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($context->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($context->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($context->id) ?></td>
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
        <h4><?= __('Related Grade Contexts') ?></h4>
        </div>
        <?php if (!empty($context->grade_contexts)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Grade Id') ?></th>
                <th scope="col"><?= __('Context Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($context->grade_contexts as $gradeContexts): ?>
            <tr>
                <td><?= h($gradeContexts->id) ?></td>
                <td><?= h($gradeContexts->grade_id) ?></td>
                <td><?= h($gradeContexts->context_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'GradeContexts', 'action' => 'view', $gradeContexts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'GradeContexts', 'action' => 'edit', $gradeContexts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'GradeContexts', 'action' => 'delete', $gradeContexts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gradeContexts->id)]) ?>
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

