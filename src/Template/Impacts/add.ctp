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
			<div class="ibox-content">
				<div class="impacts form large-9 medium-8 columns content">
            <?= $this->Form->create($impact) ?>
            <fieldset>
                <div class = 'ibox-title'>
                    <legend><?= __('Add Impact') ?></legend>
                </div>
                <?php
                    echo $this->Form->control('impact_category_id', ['options' => $impactCategories, 'empty' => '--Please Select--']);
                ?>
            </fieldset>
                <div class="form-group" id = "impacts">
                    <label class="col-sm-2 control-label" for="impacts">Impacts</label>
                    <button class="btn btn-success" data-toggle="modal" type = "button" href="#addModal">Add Impact</button>
                    <div class="impact-list col-sm-10 col-sm-offset-2">
                    </div><!-- .impact-list -->
                </div>
          
            <?= $this->Form->end() ?>
        </div>
			</div>
		</div>
	</div>
</div>
<!-- add impact modal window -->
<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);

?>
<div class="modal inmodal" id="addModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Impact</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('parent_id', ['id' => 'parentId']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'nodeId']);?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Name'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("name", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control"));
                        ?>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <?= $this->Form->label('description', __('Description'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("description", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => false,
                        "class" => "form-control"));
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"addImpact"]) ?>
            </div>
    
        </div>
    </div>
</div>

<div class="modal inmodal" id="editModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Impact</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('parent_id', ['id' => 'parent_id']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'node_id']);?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Name'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("name", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "id" => 'impactName',
                        "class" => "form-control"));
                        ?>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <?= $this->Form->label('description', __('Description'), ['class' => ['control-label'],]); ?>
                    <div>
                      <?= $this->Form->input("description", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => false,
                        "id" => 'impactDesc',
                        "class" => "form-control"));
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"updateImpact"]) ?>
            </div>
    
        </div>
    </div>
</div>

<script type="text/javascript">
    var host = $('#baseUrl').val();
    $('#impacts').hide();
    $('select[id = impact-category-id] ').on('change', function(){
      $.jstree.destroy ();
      $('#impacts').hide();
      $('#impacts').show();
        var impactCategoryId = $(this).val();
        console.log(impactCategoryId);
        var request = {
            'impact_category_id' : impactCategoryId
        };

        if(impactCategoryId == ''){
          $('#impacts').hide();
        }else{
            $.ajax({
                 url: host+"api/impacts/impactsByImpactCategories",
                 headers:{"accept":"application/json"},
                 dataType: 'json',
                 data: request,
                 type: "post",
                 success:function(data){
                    console.log(data);
                    var response = data.resData.data;
                    console.log(response);

                    $('.impact-list').jstree({ 'core' : {
                        "animation" : 0,
                        "check_callback" : true,
                        "themes" : { "stripes" : true },
                        'data' : response
                    },
                      "plugins" : [
                        "contextmenu", "search",
                        "state", "types", "wholerow"
                      ],
                      "contextmenu" : {
                                            "items" : customMenu
                                        }
                  });
                 },
                 error :function(data){
                    console.log(data);
                 }
            });
          
        }
    });


    function customMenu(node){
      console.log(node);
        var items = {
              "add" : {
                  "label" : "Add Impact",
                  "action" : function () {
                      $('#addModal').on('show.bs.modal', function (e) {
                        console.log(node);
                        $('#nodeId').val(node.id);
                        $('#parentId').val(node.original.id);
                    });

                    $('#addModal').modal('show');
                  }
                  
              },
              "rename" : {
                 "label" : "Modify Impact",   //Different label (defined above) will be shown depending on node type
                 "action" : function () {
                       $.ajax({
                            url: host+"api/impacts/view/"+node.id,
                            headers:{"accept":"application/json"},
                            dataType: 'json',
                            type: "post",
                            success:function(data){
                                $('#editModal').on('show.bs.modal', function (e) {
                                    $('#impactName').val(data.impact.name);
                                    $('#impactDesc').val(data.impact.description);
                                    $('#node_id').val(node.id);
                                    $('#parent_id').val(node.original.id);
                                });
                                $('#editModal').modal('show');
                               }
                            }); 
                    }
              },
              "delete" : {
                 "label" : "Remove Impact",
                 "action" : function () {

                    var deleteConfirm = confirm('Are you sure you want to delete impact '+node.original.name);
                    if(deleteConfirm == true){
                        $.ajax({
                                   url: host+"api/impacts/delete/"+node.original.id,
                                   headers:{"accept":"application/json"},
                                   dataType: 'json',
                                   type: "delete",
                                   success:function(data){
                                        $('.impact-list').jstree().delete_node(node);
                                    } 
                                });

                    }
                    console.log('Removed successully'); 
                }
              }
           };

           return items;
    }

    $('#addImpact').on('click' , function(){
     // alert('yeah');
      var impactCategoryId = $('select[id = impact-category-id] ').val();
      var name = $('#name').val();
      console.log(name);
      var description = $('#description').val();
      console.log(description);
      var nodeId = $('#nodeId').val();
      var parentId = $('#parentId').val();
      if(parentId == ''){
        var reqData = {
            'name' : name,
            'description' : description,
            'impact_category_id' : impactCategoryId
          }
      }else{
          var reqData = {
            'name' : name,
            'description' : description,
            'impact_category_id' : impactCategoryId,
            'parent_id' : parentId
          }
      }

      console.log(reqData);

      $.ajax({
         url: host+"api/impacts/add",
         headers:{"accept":"application/json"},
         dataType: 'json',
         data: reqData,
         type: "post",
         success:function(data){
            $('#name').val('');
            $('#description').val('');
            $('#addModal').modal('hide');
            if(nodeId == ''){
              $('.impact-list').jstree().create_node('#', data.response.data, 'last');
            }else{
              $('.impact-list').jstree().create_node(nodeId, data.response.data, 'last');
            }
         },
         error : function(){
            console.log('in error');
         }
      });
    });

    $('#updateImpact').on('click' , function(){
      var name = $('#impactName').val();
      console.log(name);
      var description = $('#impactDesc').val();
      console.log(description);
      var nodeId = $('#node_id').val();
      var parentId = $('#parent_id').val();
      var node = {
                   id : nodeId,
                   parent_id : parentId,

                 }

      var reqData = {
        'name' : name,
        'description' : description,
        // 'parent_id' : parentId
      }

      console.log(reqData);

      $.ajax({
         url: host+"api/impacts/edit/"+nodeId,
         headers:{"accept":"application/json"},
         dataType: 'json',
         data: reqData,
         type: "put",
         success:function(data){
            $('#editModal').modal('hide');
            $('.impact-list').jstree().rename_node(nodeId, data.response.data.text, 'last');
         },
         error : function(){
            console.log('in error');
         }
      });
    });



</script>
