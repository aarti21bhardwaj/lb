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
    <?= $this->Form->create($user, ['enctype'=>"multipart/form-data"]) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add User') ?></legend>
        </div>
        <?php
            echo $this->Form->control('school_id', ['required' => true,'options' => $schools, 'empty' => '--Please Select--']);
            echo $this->Form->control('first_name');
            echo $this->Form->control('middle_name');
            echo $this->Form->control('last_name');
            echo $this->Form->control('email');
            echo $this->Form->control('password');
            echo $this->Form->control('dob', ['class' => 'datepicker' , 'id'=>'datePick1' ,'placeholder' =>'Click here...']);
            echo $this->Form->control('role_id', ['options' => $roles, 'empty' => '--Please Select--']);
            echo $this->Form->control('legacy_id',['type' => 'text','label' => 'Legacy Id']);
            echo $this->Form->control('gender',['options' => $gender, 'empty' => '--Please Select--']);
            echo $this->Form->control('division_id', ['options' => $divisions, 'multiple' => 'multiple', 'id' => 'selectDivisions']);
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

			</div>
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

</script>