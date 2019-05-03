<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplateCourseStrands form large-9 medium-8 columns content">
    <?= $this->Form->create($reportTemplateCourseStrand) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Template Course Strand') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_id', ['options' => $reportTemplates]);
            echo $this->Form->control('course_id', ['options' => $courses]);
            echo $this->Form->control('grade_id', ['options' => $grades]);
            echo $this->Form->control('strand_id', ['options' => $strands]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>