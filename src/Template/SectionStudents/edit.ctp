<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="sectionStudents form large-9 medium-8 columns content">
    <?= $this->Form->create($sectionStudent) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Section Student') ?></legend>
        </div>
        <?php
            echo $this->Form->control('section_id', ['options' => $sections]);
            echo $this->Form->control('student_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->