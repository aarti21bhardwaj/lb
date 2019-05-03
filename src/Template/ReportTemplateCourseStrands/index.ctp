<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplateCourseStrands index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Course Strands') ?></h3>
        </div>
        <div class = "ibox-content">
                    <table class = 'table' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('report_template_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('grade_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('strand_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateCourseStrands as $reportTemplateCourseStrand): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateCourseStrand->id) ?></td>
                                        <td><?= $reportTemplateCourseStrand->has('report_template') ? $this->Html->link($reportTemplateCourseStrand->report_template->id, ['controller' => 'ReportTemplates', 'action' => 'view', $reportTemplateCourseStrand->report_template->id]) : '' ?></td>
                                        <td><?= $reportTemplateCourseStrand->has('course') ? $this->Html->link($reportTemplateCourseStrand->course->name, ['controller' => 'Courses', 'action' => 'view', $reportTemplateCourseStrand->course->id]) : '' ?></td>
                                        <td><?= $reportTemplateCourseStrand->has('grade') ? $this->Html->link($reportTemplateCourseStrand->grade->name, ['controller' => 'Grades', 'action' => 'view', $reportTemplateCourseStrand->grade->id]) : '' ?></td>
                                        <td><?= $reportTemplateCourseStrand->has('strand') ? $this->Html->link($reportTemplateCourseStrand->strand->name, ['controller' => 'Strands', 'action' => 'view', $reportTemplateCourseStrand->strand->id]) : '' ?></td>
                                        <td><?= h($reportTemplateCourseStrand->created) ?></td>
                                        <td><?= h($reportTemplateCourseStrand->modified) ?></td>
                                        <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $reportTemplateCourseStrand->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reportTemplateCourseStrand->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reportTemplateCourseStrand->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateCourseStrand->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
        
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
