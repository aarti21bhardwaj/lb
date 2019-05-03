<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="strands form large-9 medium-8 columns content">
    <?= $this->Form->create($strand) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Strand') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('learning_area_id', ['options' => $learningAreas]);
            echo $this->Form->control('code');
            // echo $this->Form->control('grade_id', ['options' => $grades]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>