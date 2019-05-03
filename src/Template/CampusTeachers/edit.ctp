<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="campusTeachers form large-9 medium-8 columns content">
    <?= $this->Form->create($campusTeacher) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Campus Teacher') ?></legend>
        </div>
        <?php
            echo $this->Form->control('campus_id', ['options' => $campuses]);
            echo $this->Form->control('teacher_id', ['options' => $teachers]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->