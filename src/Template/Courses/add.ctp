<?php
/**
  * @var \App\View\AppView $this
  */
?>
<!-- <link href="/webroot/css/bootstrap-colorpicker.min.css" rel="stylesheet"> -->
<?= $this->Html->css('bootstrap-colorpicker.min.css') ?>
<!-- Color picker -->
<?= $this->Html->script('bootstrap-colorpicker.min.js') ?>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="courses form large-9 medium-8 columns content">
    <?= $this->Form->create(null) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Course') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('grade_id', ['options' => $grades, 'empty' => '--Please Select--', 'id' => 'grade-id']);
            echo $this->Form->control('learning_area_id', ['options' => $learningAreas, 'empty' => '--Please Select--', 'id' => 'learning_area']);
            // echo $this->Form->control('term_id', ['options' => $terms, 'empty' => '--Please Select--']);
            echo $this->Form->control('campus_id', ['options' => $campuses, 'empty' => '--Please Select--']);
            echo $this->Form->control('teacher_id', ['options' => $teachers, 'multiple' => 'multiple', 'id' => 'selectTeachers']);
        ?>
        <div id = "strands">
        <?php
            echo $this->Form->control('strand_id', ['options' => $strands,'required'=>'required' ,'multiple' => 'multiple', 'id' => 'selectStrands']);
        ?>
        </div>
    </fieldset>
    <div id = "no_strands">
             <label class="col-sm-2 control-label" for="strand-id">Strand</label>
             <div class="strand-list col-sm-10">
             </div><!-- .strand-list --><br>
    </div>

    <div>
      <label class="col-sm-2 control-label"> Color</label>
      <div class = "col-sm-10">  
        <input type="text" class="form-control colorCode " name = "color" />
      </div><br><br><br>
    </div>
    <br>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    // colorpicker
    $('.colorCode').colorpicker();

    $("#selectTeachers").select2();
    $("#selectStrands").select2();
    var host = $('#baseUrl').val();
    document.getElementById('no_strands').style.display = 'none';
    $('select[id = grade-id]').on('change', function(){ 
        $('select[id = learning_area]').val('');
        document.getElementById('strands').style.display = 'block';
        document.getElementById('no_strands').style.display = 'none';
    });
    $('select[id = learning_area]').on('change', function(){       
        var gradeId = $('select[id = grade-id]').val();
        console.log(gradeId);
        var learningAreaId = $('select[id = learning_area]').val();
        console.log(learningAreaId);
        if(gradeId == ''){
           document.getElementById('strands').style.display = 'none';
           document.getElementById('no_strands').style.display = 'block';
           $('.strand-list').html('Please select a grade');
        }else{
           var request = {
                'learning_area_id' : learningAreaId,
                'grade_id' : gradeId
            }

            $.ajax({
               url: host+"courses/strands/",
               headers:{"accept":"application/json"},
               dataType: 'json',
               data : request,
               type: "post",
               success:function(data){
                console.log(data.response.data);
                if(data.response.data.message){
                    document.getElementById('strands').style.display = 'none';
                    document.getElementById('no_strands').style.display = 'block';
                    $('.strand-list').html(data.response.data.message);
                }else{
                    document.getElementById('strands').style.display = 'block'
                    document.getElementById('no_strands').style.display = 'none';
                      $('select[id = selectStrands]').empty();
                      $.each(data.response.data, function (i, values) {
                        console.log(values);
                        $('select[id = selectStrands]').append($('<option>', {
                            value: values.id,
                            text : values.name
                        }));

                     });  
                 }
                }
            }); 
        }
        

    });
});
    
</script>
