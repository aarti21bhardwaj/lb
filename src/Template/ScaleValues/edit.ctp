<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="scaleValues form large-9 medium-8 columns content">
    <?= $this->Form->create($scaleValue) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Scale Value') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('value');
            echo $this->Form->control('description');
            echo $this->Form->control('scale_id', ['options' => $scales]);
            echo $this->Form->control('sort_order');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->