<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reports form large-9 medium-8 columns content">
    <?= $this->Form->create($report) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_id', ['options' => $reportTemplates]);
            echo $this->Form->control('grade_id', ['options' => $grades]);
            echo $this->Form->control('report_page_id', ['options' => $reportPages, 'empty' => true]);
            echo $this->Form->control('course_id', ['options' => $courses, 'empty' => true]);
            echo $this->Form->control('sort_order');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>