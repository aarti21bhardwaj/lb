<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="learningAreas form large-9 medium-8 columns content">
    <?= $this->Form->create($learningArea) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Learning Area') ?></legend>
        </div>
        <?php
            echo $this->Form->control('curriculum_id', ['options' => $curriculums]);
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