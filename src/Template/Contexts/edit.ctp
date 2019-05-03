<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('/select2-3.5.2/select2.css') ?>
<?= $this->Html->css('/select2-bootstrap/select2-bootstrap.css') ?>
<?= $this->Html->script('/select2-3.5.2/select2.min.js') ?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="contexts form large-9 medium-8 columns content">
                    <?= $this->Form->create($context) ?>
                    <fieldset>
                        <div class = 'ibox-title'>
                            <legend><?= __('Edit Context') ?></legend>
                        </div>
                        <?php
                            echo $this->Form->control('name');
                        ?>
                        <div class="form-group">
                            <?= $this->Form->label('name', __('Select Grades'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                             <?= $this->Form->select('grades',$grades, ['id'=>'grade_id','label' => false,'value' => $contextGrades, 'class' => ['form-control','js-source-states-2', 'col-sm-10'], 'multiple'=> 'multiple', 'required' => 'required']); ?>
                             </div>
                        </div>
                    </fieldset>
                    <div class="col-sm-offset-4">
                      <?= $this->Form->button(__('Submit')) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->
<script type="text/javascript">
  $(document).ready(function(){
        $("#grade_id").select2();
    });
</script>