<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="learningAreas index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Learning Areas') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('curriculum_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($learningAreas as $learningArea): ?>
                        <tr>
                                        <td><?= $this->Number->format($learningArea->id) ?></td>
                                        <td><?= $learningArea->has('curriculum') ? $this->Html->link($learningArea->curriculum->name, ['controller' => 'Curriculums', 'action' => 'view', $learningArea->curriculum->id]) : '' ?></td>
                                        <td><?= h($learningArea->name) ?></td>
                                        <td><?= h($learningArea->created) ?></td>
                                        <td><?= h($learningArea->modified) ?></td>
                            <td class="actions">
                            <?= '<a href='.$this->Url->build(['action' => 'view', $learningArea->id]).' class="btn btn-xs btn-success">' ?>
                                <i class="fa fa-eye fa-fw"></i>
                            </a>
                            <?= '<a href='.$this->Url->build(['action' => 'edit', $learningArea->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $learningArea->id], ['confirm' => __('Are you sure you want to delete # {0}?', $learningArea->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
