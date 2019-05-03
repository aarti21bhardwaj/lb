<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="campusCourses form large-9 medium-8 columns content">
    <?= $this->Form->create(null ) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Campus Course') ?></legend>
        </div>
        <?php
            echo $this->Form->control('campus_id', ['options' => $campuses, 'empty' => '--Please Select--']);
            echo $this->Form->control('course_id', ['options' => $courses, 'id' => 'selectCourse', 'empty' => '--Please Select--']);
            echo $this->Form->control('teacher_id', ['options' => $teachers, 'multiple'=>"multiple", 'id' => 'selectTeachers']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#selectTeachers").select2(); 
});
    
</script>