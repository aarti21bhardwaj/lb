<?php

$template = [
        'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="control-label mr-auto col-sm-3" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($template);
?>
<div class="passwordBox animated fadeInDown">
      <div class="row">

        <div class="col-md-12">
          <div class="ibox-content">

            <h2 class="font-bold"><?= __('FORGET_PASSWORD') ?></h2>

            <p>
              <?= __('RESET_PASSWORD_LINK') ?>
            </p>

            <div class="row">

              <div class="col-lg-12">
               <?= $this->Form->create(null, ['id'=>'forgot-password-form','data-toggle'=>"validator", 'class' => 'form-horizontal']); ?>
               <div class="form-group">
                <?= $this->Form->label('name', __('Email')); ?>
                <div class="col-sm-12">
                 <?= $this->Form->input('email', ['label' => false, 'placeholder'=>"Email address" ,'required' => true, 'class' => ['form-control']]); ?>
                 <div class="help-block with-errors"></div>
               </div>
             </div> 
             <div class="row text-center">
               <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
             </div>
           <?= $this->Form->end() ?>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
