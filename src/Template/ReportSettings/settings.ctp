<?php
/**
  * @var \App\View\AppView $this
**/
// pr($this->Url->build('/report-settings/set_data')); die;
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
              <ul>
              	<?php foreach ($response as $data): ?>
              		<li><?= $data->name ?> &nbsp; &nbsp;<button class="btn btn-xs btn-primary" title="Configure Grade Setting" onclick="location.href = '<?= $this->Url->build(['action' => 'report', '?' => ['grade_id' => $data->id, 'report_template_id' => $reportTemplateId]]) ?>';"><i class="fa fa-gears fa-fw"></i>Grade Settings</button>
              		<?php  if(!empty($data->children)) ?>
              		<ul>
              			<?php foreach ($data->children as $value): ?>
              			<li><?= $value->name ?>&nbsp; &nbsp;<button class="btn btn-xs btn-warning" title="Configure Course Setting" onclick = <?= "openModal('".$reportTemplateId."','".$value->grade_id."','".$value->id."')" ?>><i class="fa fa-gears fa-fw"></i>Course Settings</button>&nbsp;&nbsp;&nbsp;
                    <?php 
                      $url = $this->Url->build('/report-settings/set_data');
                    ?>
                    <button class="btn btn-xs btn-success" onclick = <?= "openIframeModal('".$url."','".$reportTemplateId."','".$value->grade_id."','".$value->id."')" ?>>Choose Strands, Standards, and Impacts</button>

                    <!-- <button class="btn btn-xs btn-success " title="Set More Data" id = "moreSettings"></button> --></li>
              			<?php endforeach; ?>
              		</ul>
              		</li>
              	<?php endforeach; ?>
              </ul>      
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
<div class="modal inmodal" id="modalWindow" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Set Strands, Standards and Impacts</h4>
            </div>
            <div class="modal-body">
            <iframe  style="zoom:0.60" width="99.6%" height="500" frameborder="0"></iframe>
            </div>
      </div>
    </div>
</div>

<script type="text/javascript">

  function openIframeModal(url, reportTemplateId, gradeId,courseId){
    console.log('here in iframe modal');
    console.log(reportTemplateId);
    console.log(gradeId);
    console.log(courseId);
    console.log(url);
    url = url+'?report_template_id='+reportTemplateId+'&grade_id='+gradeId+'&course_id='+courseId;
    $('iframe').attr("src", url);
    $('#modalWindow').modal('show');
    
  }
</script>

<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-4 control-label" {{attrs}}>{{text}}</label>',
         'checkbox' => '<input type="checkbox" class="col-sm-10 control-label" name="{{name}}" value="{{value}}"{{attrs}}>',
         'button' => '<button class="btn btn-primary col-sm-offset-5" {{attrs}}>{{text}}</button>',
         'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'select' => '<div class="col-sm-8"><select name="{{name}}" class="form-control m-b" {{attrs}}>{{content}}</select></div>',
];

$this->Form->setTemplates($modalTemplate);

?>

<div class="modal inmodal" id="myModal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Apply Settings</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('report_template_id', ['id' => 'reportTemplateId']);?>
                <?= $this->Form->hidden('course_id', ['id' => 'courseId']);?>
                <?= $this->Form->hidden('grade_id', ['id' => 'gradeId']);?>
                <div class="form-group">
                      <?= $this->Form->label('course_status', __('Course Status'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("course_status", array(
                          "label" => false,
                          "id" => "course-status",
                          'required' => true,
                          'style' => "margin-top: -18px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                </div><br><br>
                <div class="form-group" id = "course_comment">
                      <?= $this->Form->label('course_comment_status', __('Course Comment'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("course_comment_status", array(
                          "label" => false,
                          "id" => "course-comment",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "course_scale_status">
                      <?= $this->Form->label('course_scale_status', __('Course Scale Status'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("course_scale_status", array(
                          "label" => false,
                          "id" => "course-scale-status",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group">
                      <?= $this->Form->label('strand_status', __('Strand Status'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("strand_status", array(
                          "label" => false,
                          "id" => "strand-status",
                          'required' => true,
                          'style' => "margin-top: -18px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "strand_comment">
                      <?= $this->Form->label('strand_comment_status', __('Strand Comment'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("strand_comment_status", array(
                          "label" => false,
                          "id" => "strand-comment",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group">
                      <?= $this->Form->label('standard_status', __('Standard Status'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("standard_status", array(
                          "label" => false,
                          "id" => "standard-status",
                          'required' => true,
                          'style' => "margin-top: -18px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "standard_comment">
                      <?= $this->Form->label('standard_comment_status', __('Standard Comment'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("standard_comment_status", array(
                          "label" => false,
                          "id" => "standard-comment",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group">
                      <?= $this->Form->label('impact_status', __('Impact Status'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("impact_status", array(
                          "label" => false,
                          "id" => "impact-status",
                          'required' => true,
                          'style' => "margin-top: -18px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "impact_comment">
                      <?= $this->Form->label('impact_comment_status', __('Impact Comment'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("impact_comment_status", array(
                          "label" => false,
                          "id" => "impact-comment",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "show_teacher_reflection">
                      <?= $this->Form->label('show_teacher_reflection', __('Teacher Reflection'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("show_teacher_reflection", array(
                          "label" => false,
                          "id" => "teacher-reflection",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "show_student_reflection">
                      <?= $this->Form->label('show_student_reflection', __('Student Reflection'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("show_student_reflection", array(
                          "label" => false,
                          "id" => "student-reflection",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <div class="form-group" id = "show_special_services">
                      <?= $this->Form->label('show_special_services', __('Special Services'), ['class' => ['control-label', 'col-sm-4']]); ?>
                      <div>
                        <?= $this->Form->checkbox("show_special_services", array(
                          "label" => false,
                          "id" => "special-services",
                          'required' => true,
                          'style' => "margin-top: -19px",
                          "class" => ["form-control"]));
                          ?>
                      </div>
                  </div><br><br>
                  <?php
                            echo $this->Form->control('report_template_page', ['options' => $reportTemplatePages, 'empty' => '--Please Select--']);
                  ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"saveSettings"]) ?>
            </div>
    
        </div>
    </div>
</div>



<script type="text/javascript">
  var host = $('#baseUrl').val();
  $(document).ready(function(){
    $('#html1').jstree();

    $('#saveSettings').on('click', function(){

        var request = {
          'report_template_id' : $('#reportTemplateId').val(),
          'course_id' : $('#courseId').val(),
          'grade_id' : $('#gradeId').val(),
          'course_status' : $('#course-status').prop('checked') * 1,
          'course_comment_status' : $('#course-comment').prop('checked') * 1,
          'course_scale_status' : $('#course-scale-status').prop('checked') * 1,
          'strand_status' : $('#strand-status').prop('checked') * 1,
          'strand_comment_status' : $('#strand-comment').prop('checked') * 1,
          'standard_status' : $('#standard-status').prop('checked') * 1,
          'standard_comment_status' : $('#standard-comment').prop('checked') * 1,
          'impact_status' : $('#impact-status').prop('checked') * 1,
          'impact_comment_status': $('#impact-comment').prop('checked') * 1,
          'show_teacher_reflection': $('#teacher-reflection').prop('checked') * 1,
          'show_student_reflection': $('#student-reflection').prop('checked') * 1,
          'show_special_services': $('#special-services').prop('checked') * 1,
          'report_template_page_id':$('#report-template-page').val()
        }
        console.log(request);

        $.ajax({
                 url: host+"report-settings/add",
                 headers:{"accept":"application/json"},
                 dataType: 'json',
                 data : request,
                 type: "post" 
              }).done(function(data){
                    $('#reportTemplateId').val('');
                    $('#courseId').val('');
                    $('#gradeId').val('');
                    $('#course-status').removeAttr('checked');
                    $('#course-comment').removeAttr('checked');
                    $('#course-scale-status').removeAttr('checked');
                    $('#strand-status').removeAttr('checked');
                    $('#strand-comment').removeAttr('checked');
                    $('#standard-status').removeAttr('checked');
                    $('#standard-comment').removeAttr('checked');
                    $('#impact-status').removeAttr('checked');
                    $('#impact-comment').removeAttr('checked');
                    $('#teacher-reflection').removeAttr('checked');
                    $('#student-reflection').removeAttr('checked');
                    $('#special-services').removeAttr('checked');
                    $('#report-template-page').val('');
                    $('#myModal').modal('hide');
                    alert("Saved Successfully");
      });  
    });
  });
  function openModal(reportTemplateId, gradeId, courseId){
        $.ajax({
                    url: host+"report-settings/fetchData",
                    headers:{"accept":"application/json"},
                    dataType: 'json',
                    data: {
                      "report_template_id" : reportTemplateId,
                      "course_id" : courseId,
                      "grade_id" : gradeId
                    },
                    type: "post",
                    success:function(data){
                      // console.log(data);
                      console.log('true')
                        $('#myModal').modal('show');
                        $('#reportTemplateId').val(data.response.report_template_id);
                        $('#courseId').val(data.response.course_id);
                        $('#gradeId').val(data.response.grade_id);
                        $('#course-status').prop('checked', data.response.course_status) * 1;
                        $('#course-comment').prop('checked', data.response.course_comment_status) * 1;
                        $('#course-scale-status').prop('checked', data.response.course_scale_status) * 1;
                        $('#strand-status').prop('checked', data.response.strand_status) * 1;
                        $('#strand-comment').prop('checked', data.response.strand_comment_status) * 1;
                        $('#standard-status').prop('checked', data.response.standard_status) * 1;
                        $('#standard-comment').prop('checked', data.response.standard_comment_status) * 1;
                        $('#impact-status').prop('checked',  data.response.impact_status) * 1;
                        $('#impact-comment').prop('checked', data.response.impact_comment_status) * 1;
                        $('#teacher-reflection').prop('checked', data.response.show_teacher_reflection) * 1;
                        $('#student-reflection').prop('checked', data.response.show_student_reflection) * 1;
                        $('#special-services').prop('checked', data.response.show_special_services) * 1;
                        $('#report-template-page').val(data.response.report_template_page_id);
                    },error:function(){
                                  // console.log('ajax error');
                                  console.log('false');
                        $('#myModal').modal('show');
                        $('#reportTemplateId').val(reportTemplateId);
                        $('#courseId').val(courseId);
                        $('#gradeId').val(gradeId);
                        $('#course-status').removeAttr('checked');
                        $('#course-comment').removeAttr('checked');
                        $('#strand-status').removeAttr('checked');
                        $('#strand-comment').removeAttr('checked');
                        $('#standard-status').removeAttr('checked');
                        $('#standard-comment').removeAttr('checked');
                        $('#impact-status').removeAttr('checked');
                        $('#impact-comment').removeAttr('checked');
                        $('#teacher-reflection').removeAttr('checked');
                        $('#student-reflection').removeAttr('checked');
                        $('#special-services').removeAttr('checked');
                        $('#report-template-page').val('');
                      }
                });
      }

</script>
