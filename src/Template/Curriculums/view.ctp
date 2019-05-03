<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($curriculum->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($curriculum->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($curriculum->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($curriculum->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($curriculum->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $this->Text->autoParagraph(h($curriculum->description)); ?></td>
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
        <h4><?= __('Related Learning Areas') ?></h4>
        </div>
        <?php if (!empty($curriculum->learning_areas)): ?>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Curriculum Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($curriculum->learning_areas as $learningAreas): ?>
            <tr>
                <td><?= h($learningAreas->id) ?></td>
                <td><?= h($learningAreas->curriculum_id) ?></td>
                <td><?= h($learningAreas->name) ?></td>
                <td><?= h($learningAreas->description) ?></td>
                <td><?= h($learningAreas->created) ?></td>
                <td><?= h($learningAreas->modified) ?></td>
                <td class="actions">
                    <?= '<a href='.$this->Url->build(['controller' => 'LearningAreas', 'action' => 'view', $learningAreas->id]).' class="btn btn-xs btn-success">' ?>
                        <i class="fa fa-eye fa-fw"></i>
                    </a>
                    <?= '<a href='.$this->Url->build(['controller' => 'LearningAreas', 'action' => 'edit', $learningAreas->id]).' class="btn btn-xs btn-warning"">' ?>
                        <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <?= $this->Form->postLink(__(''), ['controller' => 'LearningAreas', 'action' => 'delete', $learningAreas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $learningAreas->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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

