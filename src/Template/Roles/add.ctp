<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="roles form large-9 medium-8 columns content">
    <?= $this->Form->create($role) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Role') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('label');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>