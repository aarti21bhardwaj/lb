<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="divisions form large-9 medium-8 columns content">
    <?= $this->Form->create($division) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Division') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('school_id', ['options' => $schools, 'empty' => '--Please Select--', 'id' => 'school_id', 'required' => 'required']);
            echo $this->Form->control('campus_id', ['options' => $campuses, 'empty' => '--Please Select--', 'id' => 'campus_id', 'required' => 'required']);
            echo $this->Form->control('template_id', ['options' => $templates, 'empty' => '--Please Select--', 'id' => 'template_id', 'required' => 'required']);
        ?>
         <div class="form-group">
              <?= $this->Form->label('name', __('Select Grades'), ['class' => ['col-sm-2', 'control-label']]); ?>
              <div class="col-sm-10">
                 <?= $this->Form->select('grades',$grades, ['id'=>'grade_id','label' => false,'value' => $divisionGrades, 'class' => ['form-control','js-source-states-2', 'col-sm-10'], 'multiple'=> 'multiple', 'required' => 'required']); ?>
                <span class="help-block m-b-none">Grades you select can only be associated with one division for a campus</span>
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

<script type="text/javascript">
  $(document).ready(function(){
        $("#grade_id").select2();

        $('select[id = school_id]').on('change', function(){
            // destroy js tree
            
            var schoolId = $(this).val();
            var campuses = <?php echo json_encode($schoolsCampuses); ?>;
            $('select[id = campus_id]').empty();
              
            if(typeof(campuses[schoolId]) != 'undefined' && campuses[schoolId].length != 0){
              values = campuses[schoolId];
              $('select[id = campus_id]').append('<option value>--Please Select--</option>');
              $.each(values, function (i, values) {
                  
                  $('select[id = campus_id]').append($('<option>', {
                      value: values.id,
                      text : values.name
                  }));

              });
            }else{
              $('select[id = campus_id]').append('<option value>--No Campuses available for this school--</option>');
            }
        });
    });
</script>