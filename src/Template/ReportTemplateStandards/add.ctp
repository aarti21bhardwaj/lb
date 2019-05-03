<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplateStandards form large-9 medium-8 columns content">
    <?= $this->Form->create($reportTemplateStandard) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Template Standard') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_id', ['options' => $reportTemplates]);
            echo $this->Form->control('standard_id', ['options' => $standards]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>