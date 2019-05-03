<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('bootstrap-colorpicker.min.css') ?>
<!-- Color picker -->
<?= $this->Html->script('bootstrap-colorpicker.min.js') ?>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="courses form large-9 medium-8 columns content">
    <?= $this->Form->create($course) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Course') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('grade_id', ['options' => $grades]);
            echo $this->Form->control('learning_area_id', ['options' => $learningAreas]);
            // echo $this->Form->control('term_id', ['options' => $terms]);
            echo $this->Form->control('campus_id', ['options' => $campuses, 'value' => $course->campus_courses[0]->campus_id, 'disabled' => true]);
            echo $this->Form->control('teacher_id', ['options' => $teachers, 'value' => $courseTeachers, 'multiple' => 'multiple', 'id' => 'selectTeachers', 'disabled' => true]);
            echo $this->Html->link('Add More Teachers', ['controller' => 'CampusCourses', 'action' => 'view', $course->campus_courses[0]->id],['class' => ['btn', 'btn-success' ,'col-sm-offset-2']]);
        ?>
    </fieldset>
     <div>
      <label class="col-sm-2 control-label"> Color</label>
      <div class = "col-sm-10">  
        <input type="text" class="form-control colorCode " value = "<?= $course->color ?>" name = "color"/>
      </div><br><br><br>
    </div>
    <br>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->
<script type="text/javascript">
$(document).ready(function(){
     $('.colorCode').colorpicker();

    $("#selectTeachers").select2(); 
});
    
</script>