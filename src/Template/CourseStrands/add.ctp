<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="courseStrands form large-9 medium-8 columns content">
    <?= $this->Form->create(null) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add ') ?><?php echo !empty($courseData) ? __('Strands for course '.$courseData->name) : 'Course Strands'?></legend>
        </div>
        <?php
            if(!empty($courseData)){
                echo $this->Form->control('course_id', ['options' => $courses, 'value' => $courseData->id, 'disabled' => true]);
                if(!empty($strandByCourse)){
                    echo $this->Form->control('strand_id', ['options' => $strandByCourse, 'id' => 'selectStrands', 'multiple' => 'multiple']);
                    echo $this->Form->control('grade_id', ['options' => $grades, 'id' => 'selectGrades', 'multiple' => 'multiple']);
                }else{
                    echo "Please set strand for course lerning area and grade";
                }
            }else{
                echo $this->Form->control('course_id', ['options' => $courses]);
                echo $this->Form->control('strand_id', ['options' => $strands, 'id' => 'selectStrands', 'multiple' => 'multiple']);
                echo $this->Form->control('grade_id', ['options' => $grades, 'id' => 'selectGrades', 'multiple' => 'multiple']);
            }
            
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
        $("#selectStrands").select2();
        $("#selectGrades").select2();
    });
</script>