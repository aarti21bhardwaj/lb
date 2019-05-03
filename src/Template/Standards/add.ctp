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
				<div class="standards form large-9 medium-8 columns content">
                    <?= $this->Form->create($standard) ?>
                    <fieldset>
                        <div class = 'ibox-title'>
                            <legend><?= __('Add Standard') ?></legend>
                        </div>
                        <?php
                            echo $this->Form->control('curriculum', ['options' => $curriculums, 'empty' => '--Please Select--']);
                            echo $this->Form->control('learning_area', ['options' => $learningAreas, 'empty' => '--Please Select--', 'id' => 'learning_area']);
                            // echo $this->Form->control('grade_id', ['options' => $grades, 'empty' => '--Please Select']);
                        ?>

                    </fieldset>
                    <div class="form-group" id = "strands">
                        <label class="col-sm-2 control-label" for="grade-id">Standards</label>
                        <div class="strand-list col-sm-10">
                        </div><!-- .strand-list -->
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

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
                <h4 class="modal-title">Add Standard</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('strand_id', ['id' => 'strandId']);?>
                <?= $this->Form->hidden('grade_id', ['id' => 'gradeId']);?>
                <?= $this->Form->hidden('parent_id', ['id' => 'parentId']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'nodeId']);?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Standard'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("name", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        'placeholder'=>"Enter Standard Name"));
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
                        "class" => "form-control",
                        'placeholder'=>"Enter Standard description"));
                        ?>
                    </div>
                </div><br><br>
                <?php
                    echo $this->Form->control('grade_id', ['options' => $grades, 'id' => 'grade_id', 'multiple' => 'multiple']); 
                ?>
                <br><br>
                <div class="form-group">
                    <?= $this->Form->label('code', __('Code'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("code", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        'placeholder'=>"Enter Standard Code"));
                        ?>
                    </div>
                </div><br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"saveStandards"]) ?>
            </div>
    
        </div>
    </div>
</div>

<?php
    $editModalTemplate = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($editModalTemplate);

?>  

<div class="modal inmodal" id="editModal"  role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
  <!--       <?= $this->Form->create($standard, ['class' => 'form-horizontal','data-toggle'=>"validator"]) ?>
  -->       <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Standard</h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->hidden('strand_id', ['id' => 'strand_id']);?>
                <?= $this->Form->hidden('grade_id', ['id' => 'grade_id']);?>
                <?= $this->Form->hidden('parent_id', ['id' => 'parent_id']);?>
                <?= $this->Form->hidden('node_id', ['id' => 'node_id']);?>
                <div class="form-group">
                    <?= $this->Form->label('name', __('Standard'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("name", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        'id' => 'standardName',
                        'placeholder'=>"Enter Standard Name"));
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
                        "class" => "form-control",
                        'id' => 'standardDesc',
                        'placeholder'=>"Enter Standard description"));
                        ?>
                    </div>
                </div><br><br>
                 <?php
                    echo $this->Form->control('grade_id', ['options' => $grades, 'id' => 'standardGrades', 'multiple' => 'multiple']); 
                ?>
                <br><br>
                <div class="form-group">
                    <?= $this->Form->label('code', __('Code'), ['class' => ['control-label']]); ?>
                    <div>
                      <?= $this->Form->input("code", array(
                        "label" => false,
                        "type"=>"text",
                        'required' => true,
                        "class" => "form-control",
                        'id' => 'standardCode',
                        'placeholder'=>"Enter Standard Code"));
                        ?>
                    </div>
                </div><br><br>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"editStandards"]) ?>
            </div>
 <!--        <?= $this->Form->end() ?>
 -->        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var host = $('#baseUrl').val();
        document.getElementById('strands').style.display = 'none';
        $('select[id = curriculum]').on('change', function(){
            // destroy js tree
            $.jstree.destroy ();
            document.getElementById('strands').style.display = 'none';
            // reset learning area
            $('select[id = learning_area]').val('');
            // reset grade
            $('select[id = grade-id]').val('');
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

        $('select[id = learning_area]').on('change', function(){
            //destroy js tree
            $.jstree.destroy ();
            // create js tree as done below:
            var learningAreaId = $('select[id = learning_area]').val();
            document.getElementById('strands').style.display = 'block';
            var request = {
                'learning_area_id' : learningAreaId,
            }
            $.ajax({
                    url: host+"standards/standardsByStrands",
                    headers:{"accept":"application/json"},
                    dataType: 'json',
                    data: request,
                    type: "post",
                    success:function(data){
                        console.log('ajax success');
                        document.getElementById('strands').style.display = 'block';
                        var response = data.response.data;
                        console.log(response);
                        $('.strand-list').jstree({ 'core' : {
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
                    error:function(){
                        console.log('ajax error');
                        $('.strand-list').html('Please add a strand first for this learning area');
                    }
                });
            })



        function customMenu(node){
           var items = {
              "add" : {
                  "label" : "Add standard",
                  "action" : function () {
                    console.log('inside add function');
                    //Open modal - get the input
                    $('#myModal').on('show.bs.modal', function (e) {
                        console.log(node);
                        $('#nodeId').val(node.id);
                        if(typeof node.original.strand_id === 'undefined'){
                            var strand_id = node.id.split('s');
                            $('#strandId').val(strand_id[1]);
                        }else{
                            $('#strandId').val(node.original.strand_id);
                        }
                        // $('#gradeId').val(node.original.grade_id);
                        $('#parentId').val(node.original.id);
                    });

                    $('#myModal').modal('show');
                  }
              },
              "rename" : {
                 "label" : "Modify standard",   //Different label (defined above) will be shown depending on node type
                 "action" : function () {
                         $.ajax({
                            url: host+"api/standards/view/"+node.original.id,
                            headers:{"accept":"application/json"},
                            dataType: 'json',
                            type: "post",
                            success:function(data){
                                $('#editModal').on('show.bs.modal', function (e) {
                                    console.log(node);
                                    console.log(data);
                                    $('#standardName').val(data.standard.name);
                                    $('#standardDesc').val(data.standard.description);
                                    $('#standardCode').val(data.standard.code);
                                    $('#standardGrades').val(data.standard.standardGrades);
                                    
                                    $('#node_id').val(node.id);
                                    if(typeof node.original.strand_id === 'undefined'){
                                        var strand_id = node.id.split('s');
                                        $('#strand_id').val(strand_id[1]);
                                    }else{
                                        $('#strand_id').val(node.original.strand_id);
                                    }
                                    $('#grade_id').val(node.original.grade_id);
                                    $('#parent_id').val(node.original.id);
                                });
                                $('#editModal').modal('show');
                               }
                            });
                    }
              },
              "delete" : {
                 "label" : "Remove standard",
                 "action" : function () { 
                    //get selected node

                    // Ask confirmation to delete.

                    //delete on server
                    var deleteConfirm = confirm('Are you sure you want to delete standard '+node.original.name);
                    if(deleteConfirm == true){
                        $.ajax({
                                   url: host+"api/standards/delete/"+node.original.id,
                                   headers:{"accept":"application/json"},
                                   dataType: 'json',
                                   type: "delete",
                                   success:function(data){
                                        $('.strand-list').jstree().delete_node(node);
                                    } 
                                });

                    }

                    console.log('Removed successully'); 
                }
              }
           };

           if(node.original.parent_id == null) {
            items.delete._disabled = true;
            items.rename._disabled = true;
           }

           return items;
        }

        //Process save node form.
        $('#saveStandards').on('click', function(){
            console.log('in save standard function call - i got clicked');                        
            var node_id = $('#nodeId').val();
            var name = $('#name').val();
            var code = $('#code').val();
            var gradeIds = $('#grade_id').val();
            console.log(gradeIds);
            // var isSelected = $('#is_selected').val();
            var description = $('#description').val();
            var strandId = $('#strandId').val();
            // var grade_id = $('#gradeId').val();
            var parent_id = $('#parentId').val();
            var parentId = node_id;
            console.log(parentId);
            // if(typeof strandId === 'undefined'){
            //     var strand_id = node_id.split('s');
            //     strandId = strand_id[1];
            // }

            if(typeof(parentId) == 'string'){
                parentId = parent_id;

            }
            var requestNode = {
                'name' : name,
                'code' : code,
                'description' : description,
                'parent_id' : parentId,
                'grades' : gradeIds,
                'strand_id' : strandId,
            }
            creatNode(node_id, requestNode);
        });

        //Create Node via AJAX
        function creatNode(node_id, requestNode){
            console.log('in createnode');
            $.ajax({
               url: host+"api/standards/add",
               headers:{"accept":"application/json"},
               dataType: 'json',
               data : requestNode,
               type: "post" 
            }).done(function(data){
                    console.log(requestNode);
                  $('#name').val('');
                  $('#description').val('');
                  $('#grade_id').val('');
                  $('#code').val('');
                  $('#is_selected').val('');
                  $('#myModal').modal('hide');
                  $('.strand-list').jstree().create_node(node_id, data.response.data, 'last');
            });

        }

        // Process to update standards
        $('#editStandards').on('click', function(){
            console.log('inside rename function onclick');

            var name = $('#standardName').val();
            var code = $('#standardCode').val();
            var description = $('#standardDesc').val();
            var gradeIds = $('#standardGrades').val();
            var nodeId = $('#node_id').val();
            var node = {
                         id : nodeId,
                         parent_id : $('#parent_id').val(),
                         strand_id : $('#strand_id').val(),

                       }

            var request = {
                'name' : name,
                'code' : code,
                'description' : description,
                'grades' : gradeIds
            }

            $.ajax({
               url: host+"api/standards/edit/"+nodeId,
               headers:{"accept":"application/json"},
               dataType: 'json',
               data : request,
               type: "put",
               success:function(data){
                    $('#editModal').modal('hide');
                    $('.strand-list').jstree().rename_node(node, data.response.data.text);
                } 
            });
         });  
    });
</script>
<script type="text/javascript">
  $(document).ready(function(){
//        $("#grade_id").select2();
         });
</script>

