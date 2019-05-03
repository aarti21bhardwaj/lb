<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplates form large-9 medium-8 columns content">
    <?= $this->Form->create(null) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Template') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('academic_scale', ['options' => $scales, 'empty' => '--Please Select--']);
            echo $this->Form->control('impact_scale',['options' => $scales, 'empty' => '--Please Select--']);
            echo $this->Form->control('reporting_period_id', ['options' => $reportingPeriods, 'empty' => '--Please Select--']);
            // echo $this->Form->control('grades._ids', ['options' => $grades, 'empty' => '--Please Select--']);
             echo $this->Form->control('grade_id', ['options' => $grades,'required'=>'required' ,'multiple' => 'multiple', 'id' => 'selectGrades']);
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
        $("#selectGrades").select2();
    });
</script>