<?php
 $session = $this->request->session();
 $superAdminUser = $session->read('superAdminUser');
 if($superAdminUser):?>
        <div class="alert alert-info text-center">
                <?= __('You are logged as another user <strong>'.$session->read('Auth.User.first_name').'</strong>. <strong>') ?>
                <?= $this->Html->link(__('Click here'), ['controller' => 'Users', 'action' => 'exitSuperAdminLogin']) ?>
                <?= __('</strong> to exit.') ?>   
        </div>
<?php endif;?>
