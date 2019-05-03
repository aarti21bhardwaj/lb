<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="schoolUsers form large-9 medium-8 columns content">
    <?= $this->Form->create($schoolUser) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit School User') ?></legend>
        </div>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('school_id', ['options' => $schools]);
            echo $this->Form->control('legacy_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->