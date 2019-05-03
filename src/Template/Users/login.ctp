<?php


$loginFormTemplate = [
        'button' => '<button class="btn btn-primary full-width m-b" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];


$this->Form->setTemplates($loginFormTemplate);
// $logo = null;
// if(!empty($school->logo_image_path) && !empty($school->logo_image_name)){
// 	$logo = $school->logo_image_path.'/'.$school->logo_image_name;
// }
// // pr($logo); die;
// $this->set('logo',$logo);
?>

<div>
	<h3><b>Welcome to LearningBoard&reg;</b></h3>
	<!-- <img src="<?= $this->Url->build('/').$logo?>", style = "width:50%;"> -->
	<!-- <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.</p> -->
	<p>Login to get started.</p>
	<?= $this->Form->create(null,['class'=>'m-t']) ?>
	    <?= $this->Form->control('email', ['type' => 'text', 'label' => "Email"]) ?>
	    <?= $this->Form->control('password') ?>
	    <?= $this->Form->button(__('Login')); ?>
	     <strong>
	    	<a href="<?= $this->Url->build(['action' => 'forgotPassword'])?>"><small>Forgot password?</small></a>
	    </strong>
	    <br>
	<?= $this->Form->end() ?>   
	<p class="m-t"> <small style="color:white;">LearningBoard with IDP & Integrateideas &copy; 2018</small> </p>
</div>
<style type="text/css">
	body {
   		background-image: url(<?= $this->Url->build('/')."webroot/img/login_bg.jpg"?>);
	}
</style>
