<?php
/**
  * @var \App\View\AppView $this
  */
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Settings') ?></h3>
        </div>
        <div class = "ibox-content" id="html1">
                    <h4>Add Strands</h4>
                    <?php
                            echo $this->Form->control('curriculum', ['options' => $curriculums, 'empty' => '--Please Select--']);
                            echo $this->Form->control('learning_area', ['options' => $learningAreas, 'empty' => '--Please Select--', 'id' => 'learning_area']);
                            echo $this->Form->control('grade_id', ['options' => $grades, 'empty' => '--Please Select']);
                    ?>
                    <div class = "strand"></div><br>
                    <?= $this->Form->button(__('Save Strands'), ['class' => ['btn', 'btn-primary'],'id'=>"saveStrands"]) ?>
                  <div id = "strands">
                    <h4>Standards</h4>
                    <div class = "strandStandard-list"></div><br>
                    <?= $this->Form->button(__('Save'), ['class' => ['btn', 'btn-primary'],'id'=>"saveTreeData"]) ?>
                  </div>
                  <div id = "impacts">
                    <h4>Impacts</h4>
                    <div class="impact-list"></div><br>
                    <?= $this->Form->button(__('Save'), ['class' => ['btn', 'btn-primary'],'id'=>"saveImpacts"]) ?>
                 </div>
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->

<script type="text/javascript">
    var host = $('#baseUrl').val();
    $(document).ready(function(){
    $('#strands').hide();
    $('#impacts').hide();
    var reportTemplateId = "<?= $reportTemplateId ?>";
    console.log(reportTemplateId);
    var courseId = "<?= $courseId ?>";
    var gradeId = "<?= $gradeId ?>";

    setData(reportTemplateId, gradeId, courseId)

    // filter select option

    $('select[id = curriculum]').on('change', function(){
            // // destroy js tree
            $.jstree.destroy ();
            $('select[id = learning_area]').val('');
            $('select[id = grade-id]').val('');
            $('.strand').hide();
            $('#strands').hide();
            $('#impacts').hide();
            var curriculumId = $(this).val();
            var learningAreas = <?php echo json_encode($curriculumLearningAreas); ?>;
            values = learningAreas[curriculumId];
            $('select[id = learning_area]').empty();
            $('select[id = learning_area]').append('<option>--Please Select--</option>');
            $.each(values, function (i, values) {
                console.log(values);
                $('select[id = learning_area]').append($('<option>', {
                    value: values.id,
                    text : values.name
                }));

            });
    });

    $('#saveStrands').on('click', function(){
        $.jstree.destroy ();
        var learningAreaId = $('select[id = learning_area]').val();
        console.log(learningAreaId);
        var grade_id = $('select[id = grade-id]').val();
        console.log(gradeId);

        var request = {
                          "learning_area_id" : learningAreaId,
                          "grade_id" : grade_id,
                          "report_template_id" : reportTemplateId,
                          "course_id" : courseId
                      }
        console.log(request);
        $.ajax({
                url: host+"reportTemplateCourseStrands/add",
                headers:{"accept":"application/json"},
                dataType: 'json',
                data: request,
                type: "post",
                success:function(data){
                 console.log(data);
                },
                error:function(data){
                  console.log('here');
                  console.log(data)
                  $('.strand').html(data.responseJSON.message);
                }
            });
          setData(reportTemplateId, gradeId, courseId)

    });


    $('#saveTreeData').on('click', function(){
      data = $('.strandStandard-list').jstree().get_checked();
      console.log('check node');
      console.log(data);
      // console.log(x);
      var strandData = [];
      var standardData = [];
      for(x in data){
        // console.log(typeof data[x]);
        if(data[x].startsWith("s")){
          console.log(data[x]);
          var res = data[x].substr(1);
          strandData.push(res); 
        }else{
          console.log('false');
          console.log(data[x]);
          standardData.push(data[x]); 
          console.log(standardData);
        }
      }
      var request =  {
                        'report_template_id' : reportTemplateId,
                        'course_id' : courseId,
                        'grade_id' : gradeId,
                        'strand_id' : strandData,
                        'standard_id' : standardData
                     }
      console.log(request);
      $.ajax({
                url: host+"api/reportSettings/setStrandsAndStandards",
                headers:{"accept":"application/json"},
                dataType: 'json',
                data: request,
                type: "post",
                success:function(data){
                 console.log(data);
                 alert('Saved Successfully');
                },
                error:function(data){
                  console.log('here');
                  console.log(data);
                  alert(data.responseJSON.message);
                }
            });

    });

    $('#saveImpacts').on('click', function(){


      impactId = $('.impact-list').jstree().get_checked();
       
      var request =  {
                        'report_template_id' : reportTemplateId,
                        'course_id' : courseId,
                        'grade_id' : gradeId,
                        'impact_id' : impactId
                     }

      // console.log(request);
      $.ajax({
                url: host+"api/reportSettings/setImpacts",
                headers:{"accept":"application/json"},
                dataType: 'json',
                data: request,
                type: "post",
                success:function(data){
                 console.log(data);
                 alert('Saved Successfully');
                },
                error:function(data){
                  console.log('here');
                  console.log(data);
                }
            });
    });
      
});

function setData(reportTemplateId, gradeId, courseId){
      var request = {
            'report_template_id' : reportTemplateId,
            'grade_id' : gradeId,
            'course_id' : courseId
          }

              $.ajax({
                      url: host+"report-settings/standardsAndImpacts",
                      headers:{"accept":"application/json"},
                      dataType: 'json',
                      data: request,
                      type: "post",
                      success:function(data){
                        // bindSaveMoreStrands();
                        console.log(data);
                          $('#strands').show();
                          $('#impacts').show();
                          var standards = data.standards;
                          var impacts = data.impacts;

                          $('.strandStandard-list') .jstree({ 'core' : {
                              "animation" : 0,
                              "check_callback" : true,
                              "themes" : { "stripes" : true },
                              'data' : standards
                          },                          
                          "checkbox":{
                              "three_state":false,
                              'tie_selection':false
                            },
                            "plugins" : [
                               'checkbox'
                            ]
                          });

                        if(impacts){
                          $('#impacts').show();
                          $('.impact-list').jstree({ 'core' : {
                                          "animation" : 0,
                                          "check_callback" : true,
                                          "themes" : { "stripes" : true },
                                          'data' : impacts
                                      },
                                      "checkbox":{
                                        "three_state":false,
                                        'tie_selection':false,
                                        "whole_node" :true,
                                        "keep_selected_style": true,
                                      },
                                        "plugins" : [
                                          "contextmenu", "search",
                                          "state", "types", "wholerow", 'checkbox'
                                        ]
                                    });
                            // $("li.jstree-node:not(.jstree-leaf) > a").addClass("no_checkbox");
                          }
                      },
                      error:function(data){
                        console.log('here');
                        console.log(data);
                      }
      });
      
    }

</script>
<style type="text/css">
 .no_checkbox > i.jstree-checkbox {
  display:none;
}
</style>