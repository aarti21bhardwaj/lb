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
    <?= $this->Form->create(null) ?>
    <fieldset>
    <?php if(isset($courseTeachers) && !empty($courseTeachers)){?>
    <?= $this->Form->hidden('campus_course_id', ['value' => $courseTeachers[0]->campus_course->id]);?>
    <?php } ?>
        <div class = 'ibox-title'>
        <?php if(isset($courseTeachers) && !empty($courseTeachers)){?>
            <legend><?= __('Add Teachers for Course '.$courseTeachers[0]->campus_course->course->name) ?></legend>
        <?php }else{ ?>
            <legend><?= __('Add Teachers') ?></legend>
        <?php }?>
        </div>
        <?php
            // echo $this->Form->control('campus_course_id', ['options' => $campusCourses]);
            if(isset($courseTeachers) && !empty($courseTeachers)){
                echo $this->Form->control('campus_id', ['value' => $courseTeachers[0]->campus_course->campus_id, 'options' => $campuses, 'disabled' => true]);
                echo $this->Form->control('course_id', ['value' => $courseTeachers[0]->campus_course->course_id, 'options' => $courses, 'disabled' => true]);
            }else{
                echo $this->Form->control('campus_id', ['options' => $campuses]);
                echo $this->Form->control('course_id', ['options' => $courses]);
            }
            echo $this->Form->control('teacher_id', ['options' => $teachers, 'id' => 'selectTeachers', 'multiple' => 'multiple']);
            // echo $this->Form->control('is_leader');
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