<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="scaleValues index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Scale Values') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('value') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('scale_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sort_order') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('numeric_value') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scaleValues as $key => $scaleValue): ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= h($scaleValue->name) ?></td>
                            <td><?= $this->Number->format($scaleValue->value) ?></td>
                            <td><?= $scaleValue->has('scale') ? $this->Html->link($scaleValue->scale->name, ['controller' => 'Scales', 'action' => 'view', $scaleValue->scale->id]) : '' ?></td>
                            <td><?= $this->Number->format($scaleValue->sort_order) ?></td>
                            <td><?= h($scaleValue->numeric_value) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $scaleValue->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $scaleValue->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $scaleValue->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scaleValue->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                </a>
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
