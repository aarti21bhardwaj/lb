<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportSettings form large-9 medium-8 columns content">
    <?= $this->Form->create($reportSetting) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Setting') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_id', ['options' => $reportTemplates]);
            echo $this->Form->control('grade_id', ['options' => $grades]);
            echo $this->Form->control('course_id', ['options' => $courses]);
            echo $this->Form->control('course_status');
            echo $this->Form->control('course_comment_status');
            echo $this->Form->control('strand_status');
            echo $this->Form->control('strand_comment_status');
            echo $this->Form->control('standard_status');
            echo $this->Form->control('standard_comment_status');
            echo $this->Form->control('impact_status');
            echo $this->Form->control('impact_comment_status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>