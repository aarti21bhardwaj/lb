<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplateGrades form large-9 medium-8 columns content">
    <?= $this->Form->create($reportTemplateGrade) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Template Grade') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_id', ['options' => $reportTemplates]);
            echo $this->Form->control('grade_id', ['options' => $grades]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>