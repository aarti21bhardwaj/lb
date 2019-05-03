<?= $this->Html->css('jquery-ui') ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="academicYears form large-9 medium-8 columns content">
    <?= $this->Form->create($academicYear) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Academic Year') ?></legend>
        </div>
        <?php
            echo $this->Form->control('school_id', ['required' => true,'options' => $schools, 'empty' => '--Please Select--']);
            echo $this->Form->control('name');
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
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    
    $(document).on("focus", ".datepicker", function(){
        $(this).datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $(document).ready(function() {
        var wrapper         = $("#terms"); //Fields wrapper
        var add_button      = $("#yearTerms"); //Add button ID
        
        var x = 1; //initlal text box count
        
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
                $(wrapper).append(' <tr><td><input type="text" name = "terms['+x+'][name]" class="form-control" /></td><td> <input name = "terms['+x+'][start_date]" type="text" class="form-control datepicker"  readonly  placeholder= "Click here..." /></td><td><input name = "terms['+x+'][end_date]" type="text" class="form-control datepicker"  readonly  placeholder= "Click here..." /></td><td><a href="#" class="remove_field">Remove</a></td></tr>'); //add input box
                    x++; //text box increment
        });
        
        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('td').parent('tr').remove(); x--;
        })
    });
</script>