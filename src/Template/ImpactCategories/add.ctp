<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="impactCategories form large-9 medium-8 columns content">
    <?= $this->Form->create($impactCategory) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Impact Category') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>