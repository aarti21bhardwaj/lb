<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="standards index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Standards') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('strand_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($standards as $standard): ?>
                        <tr>
                                        <td><?= $this->Number->format($standard->id) ?></td>
                                        <td><?= h($standard->name) ?></td>
                                        <td><?= $standard->has('strand') ? $this->Html->link($standard->strand->name, ['controller' => 'Strands', 'action' => 'view', $standard->strand->id]) : '' ?></td>
                                        <td><?= $standard->has('grade') ? $this->Html->link($standard->grade->name, ['controller' => 'Grades', 'action' => 'view', $standard->grade->id]) : '' ?></td>
                                        <td><?= h($standard->created) ?></td>
                                        <td><?= h($standard->modified) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view',$standard->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit',$standard->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete',$standard->id], [
                            'confirm' => __('Are you sure you want to delete # {0}?',$standard->id),
                            'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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
