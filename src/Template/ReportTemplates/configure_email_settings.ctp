<?php

$template = [
        'button' => '<button class="btn btn-w-m btn-primary" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($template);

?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Configure Email Settings') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create($emailSettings, ['data-toggle'=>'validator','class' => 'form-horizontal'])?>
                <div class="form-group">
                    <?= $this->Form->label('sender_email', __('Sender Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('sender_email', ['placeholder' => 'Sender Email','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('body', __('Body'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->textarea('body', ['placeholder' => 'Body','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="col-sm-offset-6">
                            <?= $this->Form->checkbox('live_mode', ['label' => false]); ?> Enable Live Mode
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <?= $this->Form->label('test_receiver_email', __('Test Receiver Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('test_receiver_email', ['placeholder' => 'Test Receiver Emails','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <?= $this->Form->button(__('Save Settings'), ['class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel', $this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>