<?php

$template = [
        'button' => '<button class="btn btn-w-m btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($template);

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> <link rel="stylesheet" href="https://wfolly.firebaseapp.com/node_modules/sweetalert/dist/sweetalert.css">

<div class="row">
    <div class="col-lg-12">
    <!-- <div class="reportTemplates index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div  class = 'ibox-title'>
          <div style="display:inline">
              <h3 style="display:inline"><?= __('Students Reports Record') ?></h3>
          </div>
          <div style="display:inline; margin: 0 0 0 275px;">
                    <button data-toggle="modal" data-target = "#myModal" class = 'btn btn-primary'>Download All Reports</button>
              <!-- <?= $this->Html->link('Download All Reports', ['controller' => 'ReportTemplates' , 'action' => 'index'],['class' => ['btn', 'btn-primary']]);?> -->
              <?= $this->Html->link('Configure Email', ['controller' => 'ReportTemplates' , 'action' => 'configure_email_settings',$reportTemplateId],['class' => ['btn', 'btn-primary']]);?>
              <?= $this->Html->link('Send Emails', ['controller' => 'ReportTemplates' , 'action' => 'send_emails',$reportTemplateId],['class' => ['btn', 'btn-primary']]);?>
          </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                    <table class = 'table table-striped table-bordered table-hover dataTables' cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                                <th scope="col"><?= $this->Paginator->sort('Student') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Guardian') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Courses') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($studentReports as $studentReport):?>
                        <tr>
                            <td><?= h($studentReport['name']) ?> <br><br><h5><?= $this->Html->link('Download Report', ['controller' => 'Pdf', 'action' => 'report', $studentReport['id'], $reportTemplateId], ['target' => '_blank']) ?></h5>
                              <h5>
                                <a href="#" onclick="sendEmail(<?= $studentReport['id']?>)">Send Email</a>
                              </h5> 
                            </td>
                            <td>
                               <?php if(!empty($studentReport['guardian'])){
                                    foreach ($studentReport['guardian'] as $guardian):
                                ?>
                                   <?= isset($guardian['email']) ? h($guardian['email']) : '-' ?><br> 
                               <?php endforeach; }else  { ?>
                                    <?= '-' ?>
                               <?php } ?>
                            </td>
                            <td class="row">
                               <?php if(!empty($studentReport['courses'])){
                                    foreach ($studentReport['courses'] as $course):
                                ?>
                                  <div class="col-md-6">
                                    <?= h($course['name']) ?> 
                                  </div>
                                  <div class="col-md-6">
                                     <?= '<a href='.$this->Url->build('/').'teachers#/reports/section/'.$course['section_id'].'/'.$studentReport['id'].' class="btn btn-xs btn-info" title="Performance Report" target = "_blank"><i class="fa fa-rocket fa-fw"></i></a>' ?>
                                              </a>&nbsp;&nbsp;
                                     <?php if($course['status'] === null){ ?>
                                      <!-- <span style="font-size: 10px;">In-Complete</span> -->
                                      <i title="In-Complete" class="fa fa-exclamation-circle fa-2x" style="color:rgb(222, 17, 17); font-size: 20px;"></i>
                                     <?php }else if($course['status'] === false){ ?>
                                      <!-- <span style="font-size: 10px;">Pending</span> -->
                                      <i title="Pending" class="fa fa-clock-o fa-2x" style="color: '#00FFFF'; font-size: 20px;"></i>
                                     <?php }else if($course['status'] === true){ ?>
                                      <!-- <span style="font-size: 10px;">Completed</span> -->
                                      <i title="Completed" class="fa fa-check-circle fa-2x" style="color:'#00B462'; font-size: 20px;"></i>
                                     <?php } ?>
                                  </div>
                               <?php endforeach;  } ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
        </div>
        
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->

<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);

?>
<div class="modal inmodal" id="myModal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Configure Email</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('report_template_id', ['id' => 'reportTemplateId']);?>
                <div class="form-group">
                    <?= $this->Form->label('meta.email', __('Email'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("meta.email", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        'placeholder'=>"Enter your email id"));
                        ?>
                    </div>
                </div><br><br>
               <!--  <div class="form-group">
                    <?= $this->Form->label('meta.password', __('Password'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("meta.password", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => false,
                        "class" => "form-control",
                        'placeholder'=>"Enter Your Password"));
                        ?>
                    </div>
                </div><br><br> -->
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"saveSettings"]) ?>
            </div>
    
        </div>
    </div>
</div>

<script type="text/javascript">
  function sendEmail(studentId){
    var host = $('#baseUrl').val();
    var reportTemplateId = '<?= $reportTemplateId ?>';
    swal({
              title: "Are you sure you want to send email?",
              type: "success",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok",
              cancelButtonText : "Cancel",
              closeOnConfirm: true
          }, function(isConfirm){
              if(isConfirm){
                  console.log('reportTemplateId');
                  console.log(reportTemplateId);
                  console.log('studentId');
                  console.log(studentId);
                  jQuery.ajax({
                    type: "GET",
                    url: host+"api/teachers/sendEmail/"+studentId+'/'+reportTemplateId,
                    headers:{"accept":"application/json"},
                    success: function (result) {
                      console.log('in success');
                      swal("Sent!", "Email has been successfully sent to the student", "success");
                               
                    },
                    error: function(error){
                      console.log('in error');
                      swal("Cancelled", "There is a problem while sending the email. Please try again later.", "error");
                     
                    }
                });
              }
      });
  }

  
  $(document).ready(function(){
    var host = $('#baseUrl').val();
    var reportTemplateId = '<?= $reportTemplateId ?>';
    console.log(reportTemplateId);
    $('#reportTemplateId').val(reportTemplateId);

    $('#saveSettings').on('click', function(){
      var templateId = $('#reportTemplateId').val();
      // console.log(templateId);
      var email = $('#meta-email').val();
      // console.log(email);
      var password = $('#meta-password').val();
      // console.log(password);
      
      var reqData = {
                'email' : email,
                'password' : password
            }

      $.ajax({
               url: host+"reports/addReports/"+reportTemplateId,
               headers:{"accept":"application/json"},
               // dataType: 'json',
               data : reqData,
               type: "post" 
            }).done(function(data){
                  console.log(data);
                  $('#myModal').modal('hide');
                  alert('We have recieved your request and will send you a link to download the report on the email you have provided');                 
            });
    });


  });
</script>
