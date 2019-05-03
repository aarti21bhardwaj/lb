<?php if(!empty($unitReflections)){?>
<div class="row">
    <div class="col-md-6">
        <div class="ibox ">
		    <div class="ibox-title">
		        <h3>6.  To what extent did we achieve our purpose?</h3>
		    </div>
		    <div class="ibox-content">
		            <h4>How you could improve on the assessment task(s) so that you would have a more accurate picture of each student’s understanding of the central idea.</h4>
        	
        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 1){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>
				<?php  } endforeach; ?>
			</div>
			<div class="ibox-content">
		            <h4>What was the evidence that connections were made between the central idea and the transdisciplinary theme?</h4>
        	
			<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 2){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
				<?php  } endforeach; ?>
			</div>
		</div>
    </div>
    <div class="col-md-6">
        <div class="ibox ">
		    <div class="ibox-title">
		        <h3>7.  To what extent did we include the elements of the PYP?</h3>
		    </div>
		    <div class="ibox-title">
		        <h4>What were the learning experiences that enabled students to:</h4>
		    </div>
		    <div class="ibox-content">
		        <h4>develop an understanding of the concepts identified in “What do we want to learn?”</h4>

        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 3){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
			    <?php  } endforeach; ?>
			</div>
			<div class="ibox-content">
		        <h4>demonstrate the learning and application of particular transdisciplinary skills?</h4>

        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 4){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
			    <?php  } endforeach; ?>
			</div>
			<div class="ibox-content">
		        <h4>develop particular attributes of the learner profile and/or attitudes?</h4>

        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 5){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
			    <?php  } endforeach; ?>
			</div>
		</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="ibox ">
		    <div class="ibox-title">
		        <h3>8.  What student-initiated inquiries arose from the learning?</h3>
		    </div>
		    <div class="ibox-content">
		        <h4>Record a range of student-initiated inquiries and student questions and highlight any that were incorporated into the teaching and learning.</h4>

        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 7){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
			    <?php  } endforeach; ?>
			</div>
			<div class="ibox-content">
		        <h4>What student-initiated actions arose from the learning?</h4>

        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 8){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>        
			    <?php  } endforeach; ?>
			</div>
		</div>
    </div>
    <div class="col-md-6">
        <div class="ibox ">
		    <div class="ibox-title">
		        <h3> 9.  Teacher notes</h3>
		    </div>
		    <div class="ibox-content">
        	<?php  foreach($unitReflections as $key => $unitReflection):
        			if($key == 6){
        				foreach ($unitReflection as $reflection) :
        	?>
			            	<p>
			  	              <?= $reflection; ?>        		
			            	</p>
			    <?php   endforeach; ?>
				<?php  } endforeach; ?>
			</div>
		</div>
    </div>
</div>
<?php }?>