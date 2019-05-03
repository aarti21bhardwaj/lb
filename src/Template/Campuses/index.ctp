<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="campuses index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Campuses') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('school_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campuses as $campus): ?>
                        <tr>
                                        <td><?= $this->Number->format($campus->id) ?></td>
                                        <td><?= h($campus->name) ?></td>
                                        <td><?= $campus->has('school') ? $this->Html->link($campus->school->name, ['controller' => 'Schools', 'action' => 'view', $campus->school->id]) : '' ?></td>
                                        <td><?= h($campus->created) ?></td>
                                        <td><?= h($campus->modified) ?></td>
                                        
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $campus->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $campus->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $campus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campus->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                <?= '<a href='.$this->Url->build(['controller'=> 'CampusSettings','action' => 'add', $campus->id]).' class="btn btn-xs btn-primary" title="Campus Settings"><i class="fa fa-gears fa-fw"></i></a>' ?>
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
