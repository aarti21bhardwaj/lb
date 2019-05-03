<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="academicYears index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Academic Years') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= h('School Name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($academicYears as $academicYear): ?>
                        <tr>
                            <td><?= $this->Number->format($academicYear->id) ?></td>
                            <td><?= h($academicYear->name) ?></td>
                            <td><?= h($academicYear->school['name']) ?></td>
                            <td><?= h(($academicYear->start_date)) ?></td>
                            <td><?= h(($academicYear->end_date)) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $academicYear->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $academicYear->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $academicYear->id], ['confirm' => __('Are you sure you want to delete # {0}?', $academicYear->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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
