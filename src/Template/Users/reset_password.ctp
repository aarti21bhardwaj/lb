<?php

$template = [
        'button' => '<div class="form-group"><button class="btn btn-primary" {{attrs}}>{{text}}</button></div>',
            'formStart' => '<div><form class="m-t" {{attrs}}>',
            'formEnd' => '</form></div>',
            'formGroup' => '{{label}}{{input}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
            'inputContainer' => '<div class= "form-group" {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div {{type}}{{required}} error">{{content}}{{error}}</div>',
            'label' => '<label class="control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($template);
?>
        <div>
            <div>

                <h1 class="logo-name">LB</h1>

            </div>
            <h3>Welcome to LB</h3>
            <p>            </p>
            <h3>Reset Password</h3>
            <?= $this->Form->create(NULL, ['url' => ['controller' => 'Users', 'action' => 'resetPassword']]) ?>
                    <?= $this->Form->input("new_pwd", array(
                            "label" => false,
                            'required' => true,
                            'id'=>'new_pwd',
                            "type"=>"password",
                            "class" => "form-control",
                            'data-minlength'=>8,
                            'placeholder'=>"Enter Password"));
                            ?>
                    <?= $this->Form->hidden('reset-token',['value' => $resetToken]);?>
                    <?= $this->Form->input("cnf_new_pwd", array(
                            "label" => false,
                            'required' => true,
                            "class" => "form-control",
                            'id'=>'cnf_new_pwd',
                            'data-minlength'=>8,
                            'data-match'=>"#new_pwd",
                            "type"=>"password",
                            'data-match-error'=>"Whoops, these don't match",
                            'placeholder'=>"Confirm Password"));
                            ?>
                <button type="submit" class="btn btn-primary block full-width m-b">Reset password</button>
            <?= $this->Form->end() ?>

        </div>