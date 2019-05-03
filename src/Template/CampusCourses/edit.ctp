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
    <?= $this->Form->create($campusCourse) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Campus Course') ?></legend>
        </div>
        <?php
            echo $this->Form->control('campus_id', ['options' => $campuses, 'empty' => '--Please Select--']);
            echo $this->Form->control('course_id', ['options' => $courses, 'empty' => '--Please Select--']);
        ?>
       <!--  <div class="form-group">
        <div class="col-sm-10">
                <label class="col-sm-offset-6">
                    Is Leader&nbsp;<?= $this->Form->checkbox('is_legacy', ['label' => false]); ?>
                </label>
       </div>
       </div> -->
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->
<script type="text/javascript">
$(document).ready(function(){
    $("#selectTeachers").select2(); 
});
    
</script>