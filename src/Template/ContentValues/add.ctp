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
				<div class="contentValues form large-9 medium-8 columns content">
    <?= $this->Form->create($contentValue) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Content Value') ?></legend>
        </div>
        <?php
            echo $this->Form->control('content_category_id', ['options' => $contentCategories, 'empty' => '--Please Select--']);
            // echo $this->Form->control('text');
            // echo $this->Form->control('is_selectable');
            // echo $this->Form->control('parent_id', ['options' => $parentContentValues, 'empty' => true]);
        ?>
    </fieldset>
        <div class="form-group" id = "contentValues">
            <label class="col-sm-2 control-label" for="impacts">Content Values</label>
            <button class="btn btn-success" data-toggle= modal type = "button" data-target="#addContentValue">Add Values</button>
            <div class="content-value-list col-sm-10 col-sm-offset-2">
            </div><!-- .content-values-list -->
        </div>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>


<!-- add content values modal window -->
<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);

?>
<div class="modal inmodal" id="addContentValue" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Content Values</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('parent_id', ['id' => 'parentId']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'nodeId']);?>
                <div class="form-group">
                    <?= $this->Form->label('text', __('Text'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("text", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control"));
                        ?>
                    </div>
                </div><br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"addContent"]) ?>
            </div>
    
        </div>
    </div>
</div>

<!-- edit content values -->
<div class="modal inmodal" id="editModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Impact</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('parent_id', ['id' => 'parent_d']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'node_id']);?>
                <div class="form-group">
                    <?= $this->Form->label('text', __('Text'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("text", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        "id" => "contentText"));
                        ?>
                    </div>
                </div><br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"updateContent"]) ?>
            </div>
    
        </div>
    </div>
</div>

<script type="text/javascript">
    var host = $('#baseUrl').val();
    // $(document).ready(function(){
        $('#contentValues').hide();
        $('select[id = content-category-id] ').on('change', function(){
            $.jstree.destroy ();
            $('#contentValues').hide();
            $('#contentValues').show();
            var contactCategoryId = $(this).val();
            console.log(contactCategoryId);

            if(contactCategoryId == ''){
                $('#contentValues').hide();
            }else{
                $.ajax({
                 url: host+"api/contentValues/contentsByContentCategory/"+contactCategoryId,
                 headers:{"accept":"application/json"},
                 dataType: 'json',
                 type: "post",
                 success:function(data){
                    console.log(data);
                    var response = data.response.data;
                    console.log(response);

                    $('.content-value-list').jstree({ 'core' : {
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
                  "label" : "Add Content",
                  "action" : function () {
                      $('#addContentValue').on('show.bs.modal', function (e) {
                        console.log(node);
                        $('#nodeId').val(node.id);
                        $('#parentId').val(node.original.id);
                    });

                    $('#addContentValue').modal('show');
                  }
                  
              },
              "rename" : {
                 "label" : "Modify Content",   //Different label (defined above) will be shown depending on node type
                 "action" : function () {
                       $.ajax({
                            url: host+"api/contentValues/view/"+node.id,
                            headers:{"accept":"application/json"},
                            dataType: 'json',
                            type: "get",
                            success:function(data){
                                console.log(data.response.data);
                                $('#editModal').on('show.bs.modal', function (e) {
                                    $('#contentText').val(data.response.data.text);
                                    $('#node_id').val(node.id);
                                    $('#parent_id').val(node.original.id);
                                });
                                $('#editModal').modal('show');
                               }
                            }); 
                    }
              },
              "delete" : {
                 "label" : "Remove Content",
                 "action" : function () {

                    var deleteConfirm = confirm('Are you sure you want to delete content value '+node.original.text);
                    if(deleteConfirm == true){
                        $.ajax({
                                   url: host+"api/contentValues/delete/"+node.original.id,
                                   headers:{"accept":"application/json"},
                                   dataType: 'json',
                                   type: "delete",
                                   success:function(data){
                                        $('.content-value-list').jstree().delete_node(node);
                                    } 
                                });

                    }
                    console.log('Removed successully'); 
                }
              }
           };

           return items;
    }

    $('#addContent').on('click' , function(){
      var contentCategoryId = $('select[id = content-category-id] ').val();
      var text = $('#text').val();
      console.log(text);
      var nodeId = $('#nodeId').val();
      var parentId = $('#parentId').val();
      if(parentId == ''){
        var reqData = {
            'text' : text,
            'content_category_id' : contentCategoryId
          }
      }else{
          var reqData = {
            'text' : text,
            'content_category_id' : contentCategoryId,
            'parent_id' : parentId
          }
      }

      console.log(reqData);

      $.ajax({
         url: host+"api/contentValues/add",
         headers:{"accept":"application/json"},
         dataType: 'json',
         data: reqData,
         type: "post",
         success:function(data){
            console.log(data);
            $('#text').val('');
            $('#addContentValue').modal('hide');
            if(nodeId == ''){
              $('.content-value-list').jstree().create_node('#', data.response.data, 'last');
            }else{
              $('.content-value-list').jstree().create_node(nodeId, data.response.data, 'last');
            }
         },
         error : function(){
            console.log('in error');
         }
      });
    });

     $('#updateContent').on('click' , function(){
      var text = $('#contentText').val();
      var nodeId = $('#node_id').val();
      var parentId = $('#parent_id').val();
      var node = {
                   id : nodeId,
                   parent_id : parentId,

                 }

      var reqData = {
        'text' : text,
      }

      $.ajax({
         url: host+"api/contentValues/edit/"+nodeId,
         headers:{"accept":"application/json"},
         dataType: 'json',
         data: reqData,
         type: "put",
         success:function(data){
            $('#editModal').modal('hide');
            $('.content-value-list').jstree().rename_node(nodeId, data.response.data.text, 'last');
         },
         error : function(){
            console.log('in error');
         }
      });
    });


</script>
