<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="contentCategories form large-9 medium-8 columns content">
    <?= $this->Form->create($contentCategory) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Content Category') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('type');
            echo $this->Form->control('meta.heading_1');
            echo $this->Form->control('meta.heading_2');
            echo $this->Form->control('meta.heading_3');
            // echo $this->Form->control('meta');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->