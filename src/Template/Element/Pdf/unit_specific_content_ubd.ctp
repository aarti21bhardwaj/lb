<h3>Common Transfer Goals </h3>
<div class = "ibox-content">
   	<ol>
	    <?php 
	    	if(!empty($data->unit_specific_contents)){
	        	foreach ($data->unit_specific_contents as $unitSpecificContent):
				 if($unitSpecificContent->content_category->type == "common_transfer_goals"){ 
	    ?>   
            <li>
                <?= $unitSpecificContent->text; ?>
            </li>
    	<?php } ?>
		<?php endforeach; } ?>
	</ol>
</div>