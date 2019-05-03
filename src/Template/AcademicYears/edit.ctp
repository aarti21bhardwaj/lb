<?= $this->Html->css('jquery-ui') ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="academicYears form large-9 medium-8 columns content">
    <?= $this->Form->create($academicYear) ?>
    <?php   $yearStartDate = $academicYear['start_date']->format('y-m-d');
            $yearEndDate = $academicYear['end_date']->format('y-m-d');
    ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Academic Year') ?></legend>
        </div>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('school_id', ['required' => true,'options' => $schools ,'empty' => '--Please Select--']);
        ?>
        <div class="form-group" id="data_1">
            <label class="col-sm-2 control-label">
                Start Date
            </label>
            <div class="col-sm-7">
                <div class="input-group m-b">
                   <input name = "start_date" value='<?= $yearStartDate?>' type="text" class="form-control datepicker"  readonly id="datePick1" placeholder= 'Click here...' />
                </div>
            </div>
        </div>
        <div class="form-group" id="data_2">
            <label class="col-sm-2 control-label">
                End Date
            </label>
            <div class="col-sm-7">
                <div class="input-group m-b">
                   <input name = "end_date" value='<?= $yearEndDate?>' type="text" class="form-control datepicker"  readonly id="datePick2" placeholder= 'Click here...' />
                </div>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->
<script type="text/javascript">
    $(document).on("focus", ".datepicker", function(){
        $(this).datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>