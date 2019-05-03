<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplateTypes form large-9 medium-8 columns content">
    <?= $this->Form->create($reportTemplateType) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Template Type') ?></legend>
        </div>
        <?php
            echo $this->Form->control('type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>