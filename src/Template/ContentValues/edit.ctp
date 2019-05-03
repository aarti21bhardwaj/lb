<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="contentValues form large-9 medium-8 columns content">
    <?= $this->Form->create($contentValue) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Content Value') ?></legend>
        </div>
        <?php
            echo $this->Form->control('content_category_id', ['options' => $contentCategories]);
            echo $this->Form->control('text');
            echo $this->Form->control('is_selectable');
            echo $this->Form->control('parent_id', ['options' => $parentContentValues, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->