<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\ScaleValue $scaleValue
  */
?>
<!-- <div class="scaleValues view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($scaleValue->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($scaleValue->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Scale') ?></th>
            <td><?= $scaleValue->has('scale') ? $this->Html->link($scaleValue->scale->name, ['controller' => 'Scales', 'action' => 'view', $scaleValue->scale->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($scaleValue->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value') ?></th>
            <td><?= $this->Number->format($scaleValue->value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort Order') ?></th>
            <td><?= $this->Number->format($scaleValue->sort_order) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= (h($scaleValue->description))?h($scaleValue->description):'NULL' ?></td>
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
        <h4><?= __('Related Evaluation Impact Scores') ?></h4>
        </div>
        <?php if (!empty($scaleValue->evaluation_impact_scores)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Evaluation Id') ?></th>
                <th scope="col"><?= __('Scale Value Id') ?></th>
                <th scope="col"><?= __('Student Id') ?></th>
                <th scope="col"><?= __('Impact Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($scaleValue->evaluation_impact_scores as $evaluationImpactScores): ?>
            <tr>
                <td><?= h($evaluationImpactScores->id) ?></td>
                <td><?= h($evaluationImpactScores->evaluation_id) ?></td>
                <td><?= h($evaluationImpactScores->scale_value_id) ?></td>
                <td><?= h($evaluationImpactScores->student_id) ?></td>
                <td><?= h($evaluationImpactScores->impact_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EvaluationImpactScores', 'action' => 'view', $evaluationImpactScores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EvaluationImpactScores', 'action' => 'edit', $evaluationImpactScores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EvaluationImpactScores', 'action' => 'delete', $evaluationImpactScores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $evaluationImpactScores->id)]) ?>
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
        <h4><?= __('Related Evaluation Standard Scores') ?></h4>
        </div>
        <?php if (!empty($scaleValue->evaluation_standard_scores)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Evaluation Id') ?></th>
                <th scope="col"><?= __('Scale Value Id') ?></th>
                <th scope="col"><?= __('Student Id') ?></th>
                <th scope="col"><?= __('Standard Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($scaleValue->evaluation_standard_scores as $evaluationStandardScores): ?>
            <tr>
                <td><?= h($evaluationStandardScores->id) ?></td>
                <td><?= h($evaluationStandardScores->evaluation_id) ?></td>
                <td><?= h($evaluationStandardScores->scale_value_id) ?></td>
                <td><?= h($evaluationStandardScores->student_id) ?></td>
                <td><?= h($evaluationStandardScores->standard_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EvaluationStandardScores', 'action' => 'view', $evaluationStandardScores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EvaluationStandardScores', 'action' => 'edit', $evaluationStandardScores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EvaluationStandardScores', 'action' => 'delete', $evaluationStandardScores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $evaluationStandardScores->id)]) ?>
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

