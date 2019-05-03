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
<div>
	<h3>Topical Focus:</h3>
	<div class="table-responsive">
	    <table class="table table-striped table-bordered table-hover dataTables" >
	        <tbody>
	            <tr>
	                <td><?= $data['description'] ?></td>
	            </tr>
	        </tbody>
	    </table>
	</div>
</div>
<?php if(!empty($data->unit_contents)){?>
<div>
	<h3>What do you want the students to be able to do at the end of this unit?</h3>
	<div class="ibox-content">
		<h3>COMMON TRANSFER GOALS</h3>
		<ul>
			<?php
			if(!empty($data->unit_contents)){ 
				foreach ($data->unit_contents as $unitContent): 
					if($unitContent->content_category->type == "common_transfer_goals"){?>
					<li>
						<?= $unitContent->content_value->text; ?>
					</li>
					<?php } ?>
			<?php endforeach; } ?>
		</ul>
		<br>
		<h3>UNIT SPECIFIC TRANSFER GOALS</h3>
		<ul>
			<?php
			if(!empty($data->unit_specific_contents)){
			foreach ($data->unit_specific_contents as $unitSpecificContent): 
			if($unitSpecificContent->content_category->type == "common_transfer_goals"){?>
			<li>
				<?= $unitSpecificContent->text; ?>
			</li>
			<?php } ?>
			<?php endforeach; }?>
		</ul>
	</div>
</div>
<?php } ?>
