<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="standards form large-9 medium-8 columns content">
    <?= $this->Form->create($standard) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Standard') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('strand_id', ['options' => $strands]);
            echo $this->Form->control('grade_id', ['options' => $grades]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->