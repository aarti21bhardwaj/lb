<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('jquery-ui') ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportingPeriods form large-9 medium-8 columns content">
    <?= $this->Form->create($reportingPeriod) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Reporting Period') ?><?php echo $termData ? __(' for term '.$termData->name) : '' ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            // echo $this->Form->control('start_date');
            // echo $this->Form->control('end_date');
            // echo $this->Form->control('closing_date');
            // echo $this->Form->control('term_id', ['options' => $terms]);
        ?>
        <div class="form-group" id="data_1">
            <label class="col-sm-2 control-label">
                Start Date
            </label>
            <div class="col-sm-7">
                <div class="input-group m-b">
                   <input name = "start_date" type="text" class="form-control datepicker"  readonly id="datePick1" placeholder= 'Click here...' />
                </div>
            </div>
        </div>
        <div class="form-group" id="data_2">
            <label class="col-sm-2 control-label">
                End Date
            </label>
            <div class="col-sm-7">
                <div class="input-group m-b">
                   <input name = "end_date" type="text" class="form-control datepicker"  readonly id="datePick2" placeholder= 'Click here...' />
                </div>
            </div>
        </div>
        <div class="form-group" id="data_2">
            <label class="col-sm-2 control-label">
                Closing Date
            </label>
            <div class="col-sm-7">
                <div class="input-group m-b">
                   <input name = "closing_date" type="text" class="form-control datepicker"  readonly id="datePick3" placeholder= 'Click here...' />
                </div>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>
<script>
    $(document).on("focus", ".datepicker", function(){
        $(this).datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>