<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="schools form large-9 medium-8 columns content">
    <?= $this->Form->create($schoo,['enctype'=>"multipart/form-data"]) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit School') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
        ?>
         <div class="form-group">
            <?= $this->Form->label('logo_image', __('School Logo'), ['class' => ['col-sm-2', 'control-label']]); ?>
         <div class="col-sm-4">
            <div class="img-thumbnail">
                    <?= $this->Html->image($school->image_url, array('height' => 100, 'width' => 100,'id'=>'upload-img')); ?>
                </div>
                <br> </br>
                <?= $this->Form->input('logo_image_name', ['accept'=>"image/*",'label' => false,['class' => 'form-control'],'type' => "file",'id'=>'imgChange'] ); ?>
                <i class="fa fa-lg fa-info-circle"></i><strong> The file extensions that you may add here are JPG, PNG and JPEG. 
                    <strong>
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