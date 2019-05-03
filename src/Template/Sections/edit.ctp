<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="sections form large-9 medium-8 columns content">
    <?= $this->Form->create($section) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Section') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('course_id', ['options' => $courses]);
            echo $this->Form->control('term_id', ['options' => $terms]);
            echo $this->Form->control('teacher_id', ['options' => $teachers]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->