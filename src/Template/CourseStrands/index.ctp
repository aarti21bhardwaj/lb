<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="courseStrands index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Course Strands') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('strand_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courseStrands as $courseStrand): ?>
                        <tr>
                                        <td><?= $this->Number->format($courseStrand->id) ?></td>
                                        <td><?= h($courseStrand->course->name) ?></td>
                                        <td><?= h($courseStrand->strand->name) ?></td>
                                        <td><?= h($courseStrand->grade->name) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $courseStrand->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $courseStrand->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $courseStrand->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courseStrand->id)]) ?>
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
