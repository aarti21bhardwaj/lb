<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
	
	
?>
<h3 style="display:inline"><b>Course: </b></h3> <p style="display:inline"><?= h(implode(', ',$courses))?></p>
<br>
<br>
<h3 style="display:inline"><b>Team: </b></h3> <p style="display:inline"><?= h(implode(', ',$teachers))?></p>
<br>
<br>
<h3 style="display:inline"><b>Start Date: </b></h3> <p style="display:inline"><?= $data['start_date']?></p>
<br>
<br>
<h3 style="display:inline"><b>End Date: </b></h3> <p style="display:inline"><?= $data['end_date']?></p>
<br>
<br>
<h3 style="display:inline"><b>Unit Description:</b></h3>
<div style="display:inline" class="table-responsive">
    <table class="table table-striped table-bordered table-hover dataTables" >
        <tbody>
            <tr>
                <td><?= $data['description'] ?></td>
            </tr>
        </tbody>
    </table>
</div>