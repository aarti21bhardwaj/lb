<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;
?>
<div class = "ibox-content">
    <h3>What key understandings will students need to successfully demonstrate their learning? </h3>
    <div class = "ibox-content">
	        <?php if(!empty($data->unit_contents)){ ?>
    		<h3>Common Understanding </h3>
	        <?php 
		        foreach ($data->unit_contents as $unitContent): 
		            if($unitContent->content_category->type == "common_understandings"){?>
		    			<ul>
		                    <li>
		                        <?= $unitContent->content_value->text; ?>
		                    </li>
		    			</ul>
	            	<?php } ?>
	        	<?php endforeach; ?>
	        <?php } ?>
        <br>
	        <?php if(!empty($data->unit_specific_contents)){ ?>
	        <h3>Unit Specific Understanding </h3>
		       	<ul>
	        	<?php 
			        	foreach ($data->unit_specific_contents as $unitSpecificContent):
						 if($unitSpecificContent->content_category->type == "common_understandings"){ 
		    	?>   
	                    <li>
	                        <?= $unitSpecificContent->text; ?>
	                    </li>
	        	<?php } ?>
        		<?php endforeach;  ?>
	    		</ul>
	    		<?php } ?>
    </div>
</div>
<div class = "ibox-content">
    <h3>What key questions will drive their learning and help them to identify the ways in which they will demonstrate their learning ? </h3>
    <div class = "ibox-content">
	        <?php if(!empty($data->unit_contents)){ ?>
    			<h3>Common Question </h3>
		        <?php foreach ($data->unit_contents as $unitContent): 
		            	if($unitContent->content_category->type == "common_questions"){?>
		    			<ul>
		                    <li>
		                        <?= $unitContent->content_value->text; ?>
		                    </li>
		    			</ul>
		            <?php } ?>
		        <?php endforeach; ?>
	        <?php } ?>
        <br>
	        <?php if(!empty($data->unit_specific_contents)){ ?>
        	<h3>Unit Specific Question </h3>
		        <?php foreach ($data->unit_specific_contents as $unitSpecificContent):
					if($unitSpecificContent->content_category->type == "common_questions"){ 
			    ?>   
	       			<ul>
	                    <li>
	                        <?= $unitSpecificContent->text; ?>
	                    </li>
	    			</ul>
	        		<?php } ?>
	    		<?php endforeach;  ?>
	    	<?php } ?>
    </div>
</div>