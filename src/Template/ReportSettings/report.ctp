<?php
/**
  * @var \App\View\AppView $this
**/
// pr($this->Url->build('/report-settings/set_data')); die;

  $modalTemplate = [
         'button' => '<button {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __(' Grade Settings') ?></h3>
        </div>
        <div class = "ibox-content" style="height: 350px;">
            <div class = "pull-right">
              <button class="btn btn-xs btn-warning" onclick = <?= "openModal('".$reportTemplateId."','".$gradeId."')" ?>>Add More Pages</button>
            </div><br>
            <div id="html1">
              <div id = "closeButton"></div>
            </div><br><br>
            <div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
                          <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveReport']) ?>
                          <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                      </div>
              </div>     
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->

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

<!-- modal window -->
<div class="modal inmodal" id="myModal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Page</h4>
            </div>
            <div class="modal-body" style="height: 109px;">
                  <?= $this->Form->hidden('report_template_id', ['id' => 'reportTemplateId']);?>
                <?= $this->Form->hidden('grade_id', ['id' => 'gradeId']);?>
                  <?php
                          echo $this->Form->control('report_page_id', ['options' => $reportPages, 'empty' => '--Please Select--']);
                  ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Save'), ['class' => ['btn', 'btn-primary'],'id'=>"save"]) ?>
            </div>
      </div>
    </div>
</div>


<script type="text/javascript">
  var host = $('#baseUrl').val();
  var response = <?php echo json_encode($response); ?>;
  var reportTemplateId = <?php echo json_encode($reportTemplateId); ?>;
  var gradeId = <?php echo json_encode($gradeId); ?>;
  console.log(gradeId);
  var totalNode = response.length;
  function openModal(reportTemplateId, gradeId){
    console.log('here'); 
    console.log(totalNode);
    console.log(reportTemplateId);
    console.log(gradeId);
    console.log('in modal');
    $('#myModal').modal('show');
    $('#reportTemplateId').val(reportTemplateId);
    $('#gradeId').val(gradeId);
  }
  $(document).ready(function(){
    $('#html1').jstree({ 'core' : {
            "animation" : 0,
            "check_callback" : true,
            "themes" : { "stripes" : true },
            'data' : response
        },
        'types' : {
          "file" : {
            "valid_children" : []
          }
        },                          
          "plugins" : ['dnd','types', "contextmenu"],
           "contextmenu" : {
                              "items" : customMenu
                           }
        });
    var treeState = false;
    var reqData = [];
    $('#html1').on("move_node.jstree", function (e, data)  {
      treeState =  $('#html1').jstree().get_json();
      console.log(treeState);
    });

    function customMenu(node){
      var items = {
        "delete" : {
                 "label" : "Remove Page",
                 "action" : function () { 
                    var deleteConfirm = confirm('Are you sure you want to remove page '+node.original.text);
                    if(deleteConfirm == true){
                        $.ajax({
                                   url: host+"api/reports/delete/"+node.original.report_page_id,
                                   headers:{"accept":"application/json"},
                                   dataType: 'json',
                                   type: "delete",
                                   success:function(data){
                                        $('#html1').jstree().delete_node(node);
                                    } 
                                });

                    }

                    console.log('Removed successully'); 
                }
          }
      };
      if(node.original.object_name == "course") {
            items.delete._disabled = true;
        }
      return items;
    }
    $('#saveReport').on('click', function(){
          console.log(treeState);
          // treeState = {'1' : true, '2' :true};
          $.ajax({
              url: host+"api/reports/updateReports?report_template_id="+reportTemplateId+"&grade_id="+gradeId,
              headers:{"accept":"application/json", "content-type": "application/json"},
              data: JSON.stringify(treeState),
              dataType: 'json',
              type: "post",
              success:function(data){
               alert("Saved Successfully");
               console.log(data)
              },
              error:function(data){
                console.log('here');
                console.log(data);
              }
          });
    });
   
  });

  $('#save').on('click', function(){
    var request = {
        'report_template_id' : $('#reportTemplateId').val(),
        'grade_id' : $('#gradeId').val(),
        'report_page_id' : $('#report-page-id').val()
      }
    console.log(request);
    $.ajax({
                url: host+"api/reports/add",
                headers:{"accept":"application/json"},
                dataType: 'json',
                data: request,
                type: "post",
                success:function(data){
                 console.log(data);
                 alert("Saved Successfully");
                 window.location.reload();
                 $('#myModal').modal('hide');
                 $('#reportTemplateId').val('');
                 $('#gradeId').val('');
                 $('#report-page-id').val('');

                 $('#html1').jstree().create_node('#', data.response.data, 'last');
                 reportData(reportTemplateId, gradeId);

                },
                error:function(data){
                  // console.log('here');
                  console.log(data);
                  $('#myModal').modal('hide');
                  alert(data.responseJSON.message);
                }
            });
  });

  // function reportData(reportTemplateId, gradeId){
  //   console.log(reportTemplateId);
  //   console.log(gradeId);
  //   $.ajax({
  //               url: host+"report-settings/report?grade_id=g"+gradeId+"&report_template_id="+reportTemplateId,
  //               headers:{"accept":"application/json"},
  //               dataType: 'json',
  //               type: "get",
  //               success:function(data){
  //                console.log(data);
  //               },
  //               error:function(data){
  //                 // console.log('here');
  //                 console.log(data);
  //               }
  //           });
  // }
</script>