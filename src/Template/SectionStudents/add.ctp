<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="sectionStudents form large-9 medium-8 columns content">
    <?= $this->Form->create(null) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Section Student') ?></legend>
        </div>
        <?php
            if(!empty($sectionData)){
                echo $this->Form->control('section_id', ['options' => $sections, 'value' => $sectionData->id]);
            }else{
                echo $this->Form->control('section_id', ['options' => $sections, 'empty' => '--Please Select--']);
            }
            echo $this->Form->control('student_id', ['id' => 'selectStudents', 'multiple' => 'multiple']);
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
    $("#selectStudents").select2(); 
});
    
</script>