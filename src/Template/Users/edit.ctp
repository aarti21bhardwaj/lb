<?= $this->Html->css('jquery-ui') ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="users form large-9 medium-8 columns content">
                    <?= $this->Form->create($user,['enctype'=>"multipart/form-data"]) ?>
                    <fieldset>
                        <div class = 'ibox-title'>
                            <legend><?= __('Edit User') ?></legend>
                        <?= $this->Form->hidden('userId',['value' => $user->id]);?>
                        </div>
                        <?php
                            echo $this->Form->control('school_id', ['required' => true,'options' => $schools, 'empty' => '--Please Select--', 'value' => $user->school_id]);
                            echo $this->Form->control('first_name');
                            echo $this->Form->control('middle_name');
                            echo $this->Form->control('last_name');
                            echo $this->Form->control('email');
                            // echo $this->Form->control('password');
                            if(in_array($loggedInUser['role_id'], [1,2])){
                            ?>
                            <div class="form-group">
                              <?= $this->Form->label('name', __('Password'), ['class' => ['col-sm-2', 'control-label']]); ?>
                              <div class="col-sm-10">
                                <div class="">
                                  <a data-toggle="modal" id="changePasswordButton" class="btn btn-success" href="#changePasswordModal">Change Password</a>
                                </div>
                              </div>
                            </div>
                            <?php } 
                            echo $this->Form->control('dob', ['class' => 'datepicker' , 'id'=>'datePick1' ,'placeholder' =>'Click here...']);
                            echo $this->Form->control('role_id', ['options' => $roles]);
                            // echo $this->Form->control('image_path');
                            // echo $this->Form->control('image_name');
                            echo $this->Form->control('division_id', ['options' => $divisions, 'value' => $userDivisions ,'multiple' => 'multiple', 'id' => 'selectDivisions']);
                        ?>
                        <div class="form-group">
                        <?= $this->Form->label('image', __('Profile Image'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-4">
                            <div class="img-thumbnail">
                                    <?= $this->Html->image($user->image_url, array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
                                </div>
                                <br> </br>
                                <?= $this->Form->input('image_name', ['accept'=>"image/*",'label' => false,['class' => 'form-control'],'type' => "file",'id'=>'imgChange'] ); ?>
                                <i class="fa fa-lg fa-info-circle"></i><strong> The file extensions that you may add here are JPG, PNG and JPEG. 
                                    <strong>
                            </div> 
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <label class="col-sm-offset-6">
                                    <?= $this->Form->checkbox('is_active', ['label' => false]); ?> Active
                                </label>
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

<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-4 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);

?>
<div class="modal fade" tabindex="-1" role="dialog" id="changePasswordModal">
  <div class="modal-dialog" role="document">
    <?= $this->Form->create(null, ['class' => 'form-horizontal','data-toggle'=>"validator"]) ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= __('CHANGE_PASSWORD')?></h4>
      </div>

      <div class="modal-body">
        <strong>Password complexity:</strong><br>
          - Must not contain three or more contiguous characters of your account name or full name.<br>
         - New Password cannot be same as any of your last six passwords.<br><br>
        <div class="alert" id="rsp_msg" style='display: none;'>

        </div> 
          <div class="form-group">
            <?= $this->Form->label('name', __('New Password'), ['class' => ['col-sm-4', 'control-label']]); ?>
            <div class="col-sm-8">
              <?= $this->Form->input("new_pwd", array(
                "label" => false,
                'id'=>'new_pwd',
                "type"=>"password",
                'required' => true,
                "class" => "form-control",'data-minlength'=>8,
                'placeholder'=>"Enter New Password"));
                ?>
                <div class="help-block with-errors"><?= __('Minimum of 8 characters')?></div>
              </div>
            </div>

            <div class="form-group">
              <?= $this->Form->label('name', __('Confirm New Password'), ['class' => ['col-sm-4', 'control-label']]); ?>
              <div class="col-sm-8">
                <?= $this->Form->input("cnf_new_pwd", array(
                  "label" => false,
                  "type"=>"password",
                  'id'=>'cnf_new_pwd',
                  'required' => true,
                  "class" => "form-control",'data-minlength'=>8,'data-match'=>"#new_pwd",'data-match-error'=>"__('MISMATCH')",'placeholder'=>"Confirm Password"));
                  ?>
                  <div class="help-block with-errors"><?= __('Minimum of 8 characters')?></div>
                </div>
              </div>


            </div>
            <div class="modal-footer text-center">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'],'id'=>"saveUserPassword"]) ?>
            </div>
            <?= $this->Form->end() ?>
      </div>
    </div>
  </div>

<style type ="text/style">
.img-thumbnail {
    background: #fff none repeat scroll 0 0;
    height: 200px;
    margin: 10px 5px;
    padding: 0;
    position: relative;
    width: 200px;
}
.img-thumbnail img {
    border :1px solid #dcdcdc;
    max-width: 100%;
    object-fit: cover;
}
</style>
<script type ="text/javascript">  
     $(document).ready(function(){
        $("#selectDivisions").select2();
    }); 
    function uploadImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#upload-img').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgChange").change(function(){
        uploadImage(this);
    });

     $(document).on("focus", "#datePick1", function(){
        $(this).datepicker({ dateFormat: 'yy-mm-dd' });
    });

    var host = $('#baseUrl').val();
    $('#saveUserPassword').on('click',function(event){
        if($(this).hasClass('disabled')){
            event.preventDefault();
        }
        var userId = $('input[name=userId]').val();
        console.log(userId);
        var newPwd = $('#new_pwd').val();
        console.log(newPwd);
        var cnfNewPwd = $('#cnf_new_pwd').val();
        console.log(cnfNewPwd);
        if(newPwd && cnfNewPwd && (newPwd == cnfNewPwd)){
            $.ajax({
                url: host+"users/updatePassword/",
                headers:{"accept":"application/json"},
                dataType: 'json',
                data:{
                    "user_id" : userId,
                    "new_password" : newPwd,
                },
                type: "post",
                success:function(data){
                    if($('#rsp_msg').hasClass('alert-danger')){
                        $('#rsp_msg').removeClass('alert-danger');
                    }
                    if($('#rsp_msg').hasClass('alert-success')){
                        $('#rsp_msg').removeClass('alert-success');
                    }
                    $('#rsp_msg').addClass('alert-success');
                    $('#rsp_msg').append('<strong>Password changed successfully.</strong>');
                    $('#rsp_msg').show();
                    setTimeout(function(){
                        $('#rsp_msg').fadeIn(500);
                        $('#changePasswordModal').modal('hide');
                            $('#rsp_msg').removeClass('alert-success');
                        $('#rsp_msg').hide();
                        $('#rsp_msg').html('');
                    }, 2000);
                },
                error:function(data){
                    var className = 'alert-danger';
                    if($('#rsp_msg').hasClass('alert-success')){
                        $('#rsp_msg').removeClass('alert-success');
                    }
                    $('#rsp_msg').addClass(className);
                    $('#rsp_msg').append('<strong>' + data.responseJSON.message + '</strong>');
                    setTimeout(function(){
                        if($('#rsp_msg').hasClass(className)){
                            $('#rsp_msg').removeClass(className);
                        }
                        $('#rsp_msg').hide();
                        $('#rsp_msg').html('');

                    }, 2000);
                    $('#rsp_msg').fadeIn(500);

                },
                beforeSend: function() {

                }
            });

        }
        event.preventDefault();
    });
</script>