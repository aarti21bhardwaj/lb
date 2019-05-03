<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="impacts form large-9 medium-8 columns content">
    <?= $this->Form->create($impact) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Impact') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('impact_category_id', ['options' => $impactCategories]);
            echo $this->Form->control('parent_id', ['options' => $parentImpacts, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->