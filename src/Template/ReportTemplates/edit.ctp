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
    <?= $this->Form->create($reportTemplate) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Report Template') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('academic_scale', ['options' => $scales]);
            echo $this->Form->control('impact_scale', ['options' => $scales]);
            echo $this->Form->control('reporting_period_id', ['options' => $reportingPeriods]);
            // echo $this->Form->control('grades._ids', ['options' => $grades]);
            echo $this->Form->control('grade_id', ['options' => $grades,'required'=>'required' ,'multiple' => 'multiple', 'id' => 'selectGrades', 'value' => $reportTemplateGradeIds]);
        ?>
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
        $("#selectGrades").select2();
    });
</script>