<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="courses index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Courses') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>

                                        <th scope="col">No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">Learning Area</th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $key => $course): ?>
                        <tr>
                                        <td><?= $this->Number->format($course->id) ?></td>
                                        <td><?= h($course->name) ?></td>
                                        <td><?= h($course->grade->name) ?></td>
                                        <!-- <td><?= h($course->created) ?></td> -->
                                        <!-- <td><?= h($course->modified) ?></td> -->
                                        <td><?= h($course->learning_area->name) ?></td>
                                        <!-- <td><?= h($course->term->name) ?></td> -->
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $course->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $course->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $course->id], ['confirm' => __('Are you sure you want to delete # {0}?', $course->id)]) ?>
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
