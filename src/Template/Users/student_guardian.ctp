<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="studentGuardians form large-9 medium-8 columns content">
    <?= $this->Form->create($studentGuardian) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Guardian for {0}',$studentName) ?></legend>
        </div>
        <?php
            echo $this->Form->control('guardian_id', ['options' => $guardians,'empty' => '--Please Select--']);
            echo $this->Form->control('relationship_type', ['options'=> $relationType,'empty' => '--Please Select--']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

            </div>
        </div>
    </div>
</div>