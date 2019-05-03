<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="sectionStudents index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Section Students') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('section_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('student_id') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sectionStudents as $sectionStudent): ?>
                        <tr>
                                        <td><?= $this->Number->format($sectionStudent->id) ?></td>
                                        <td><?= $sectionStudent->has('section') ? $this->Html->link($sectionStudent->section->name, ['controller' => 'Sections', 'action' => 'view', $sectionStudent->section->id]) : '' ?></td>
                                        <td><?= $this->Number->format($sectionStudent->student_id) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $sectionStudent->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sectionStudent->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sectionStudent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sectionStudent->id)]) ?>
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
