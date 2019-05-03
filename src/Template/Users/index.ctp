<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="users index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Users') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('first_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('middle_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('last_name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('dob') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('role') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                                        <td><?= $this->Number->format($user->id) ?></td>
                                        <td><?= h($user->first_name) ?></td>
                                        <td><?= empty($user->middle_name) ? '-' : $user->middle_name ?></td>
                                        <td><?= h($user->last_name) ?></td>
                                        <td><?= h($user->email) ?></td>
                                        <td><?= h($user->dob) ?></td>
                                        <td><?= h($user->role->name) ?></td>
                                        <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $user->id]).' class="btn btn-xs btn-success">' ?>
                                            <i class="fa fa-eye fa-fw"></i>
                                        </a>
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $user->id]).' class="btn btn-xs btn-warning"">' ?>
                                        <i class="fa fa-pencil fa-fw"></i>
                                        </a>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $user->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        <?php if($user['role_id'] != 1) {?>
                                            <?= '<a href='.$this->Url->build(['controller'=> 'Users','action' => 'loginThroughSuperAdmin', $user->id]).' class="btn btn-xs btn-info" title="Login to this User"><i class="fa fa-rocket fa-fw"></i></a>' ?>
                                        <?php } ?>
                              </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->