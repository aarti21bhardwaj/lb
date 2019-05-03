<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="sections index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Sections') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('campus_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('term_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('teacher_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('division_id') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sections as $section):?>
                        <tr>
                                        <td><?= $this->Number->format($section->id) ?></td>
                                        <td><?= h($section->name) ?></td>
                                        <td><?= h($campus[$section->term_id]->name) ?></td>
                                        <td><?= h($section->course->name) ?></td>
                                        <td><?= h($section->term->name) ?></td>
                                        <td><?= h($section->teacher->first_name.' '.$section->teacher->last_name) ?></td>
                                        <td><?= isset($section->term->division->name) ? $section->term->division->name : '' ?></td>
                                        <!-- <td><?= h($section->created) ?></td>
                                        <td><?= h($section->modified) ?></td> -->
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $section->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $section->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $section->id], ['confirm' => __('Are you sure you want to delete # {0}?', $section->id)]) ?>
                                <?= '<a href='.$this->Url->build(['controller'=> 'SectionStudents','action' => 'add', $section->id]).' class="btn btn-xs btn-info" title="Add Student"><i class="fa fa-plus-square-o fa-fw"></i></a>' ?>
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
