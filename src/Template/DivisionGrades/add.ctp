<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="divisionGrades form large-9 medium-8 columns content">
    <?= $this->Form->create($divisionGrade) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Division Grade') ?></legend>
        </div>
        <?php
            echo $this->Form->control('division_id', ['options' => $divisions]);
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