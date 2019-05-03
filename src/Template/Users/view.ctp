<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($user->first_name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('First Name') ?></th>
            <td><?= h($user->first_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Middle Name') ?></th>
            <td><?= empty($user->middle_name) ? '-' : $user->middle_name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Name') ?></th>
            <td><?= h($user->last_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('DOB') ?></th>
            <td><?= h($user->dob) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= h($user->role->name) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->
<?php if($user->role_id == 4){?>
 <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h2><?= __('Guardians') ?><?= '<a href='.$this->Url->build(['action' => 'studentGuardian', $user->id,$user->first_name." ".$user->last_name]).' class="btn btn-info pull-right" title="Add Student Guardian"><i class="fa fa-plus-square-o fa-fw"></i>Add Guardian</a>' ?></h2>
        </div>
        <div class="ibox-content">
        <table class="table" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Relationship') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($studentGuardian as $studentGuardian): ?>
             <tr>
                <td><?= h($studentGuardian->guardian->first_name." ".$studentGuardian->guardian->last_name) ?></td>
                <td><?= h($studentGuardian->relationship_type) ?></td>
                <td class="actions">
                    <?= $this->Form->postLink(__(''), ['action' => 'deleteStudentGuardian', $studentGuardian->id], ['confirm' => __('Are you sure you want to delete # {0}?', $studentGuardian->guardian->first_name." ".$studentGuardian->guardian->last_name), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                </td>
            </tr> 
            <?php endforeach; ?>
        </table>
        </div><!-- .ibox-content end -->
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->
<?php } ?>
