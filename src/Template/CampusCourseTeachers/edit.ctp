<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="campusCourseTeachers form large-9 medium-8 columns content">
    <?= $this->Form->create($campusCourseTeacher) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Campus Course Teacher') ?></legend>
        </div>
        <?php
            // echo $this->Form->control('campus_course_id', ['options' => $campusCourses]);
            echo $this->Form->control('teacher_id', ['options' => $teachers, 'disabled' => true]);
            // echo $this->Form->control('is_leader');
        ?>
        <div class="form-group">
        <div class="col-sm-10">
                <label class="col-sm-offset-6">
                    Is Leader&nbsp;<?= $this->Form->checkbox('is_leader', ['label' => false]); ?>
                </label>
       </div>
       </div> 
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->