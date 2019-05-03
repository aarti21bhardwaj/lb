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
            <legend><?= __('Add Campus Teacher') ?></legend>
        </div>
        <?php
            echo $this->Form->control('campus_id', ['options' => $campuses]);
            ?>

         <div class="form-group">
                      <?= $this->Form->label('name', __('Select Teachers'), ['class' => ['col-sm-2', 'control-label']]); ?>
                      <div class="col-sm-10">
            <?= $this->Form->select('teacher_id',$teachers, ['id'=>'teacher_id','label' => false, 'class' => ['form-control','js-source-states-2', 'col-sm-10'], 'multiple'=> 'multiple', 'required' => 'required']); ?>
        </div>
    </div>
        
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
        $("#teacher_id").select2();
        });
    </script>