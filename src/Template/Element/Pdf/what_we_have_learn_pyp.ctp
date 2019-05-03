<div class="ibox ">
    <div class="ibox-title">
        <h3>3.  How might we know what we have learned?</h3>
    </div>
    
    <div class="ibox-content">
        <h4>What are the possible ways of assessing students’ prior knowledge and skills?  What evidence will we look for?</h4>
    <?php if(!empty($data->unit_specific_contents)){
            foreach ($data->unit_specific_contents as $specificContent):
                if($specificContent->content_category->type == 'assessment_evidence'){
    ?>
	    	<p><?= $specificContent->text ?></p>
    <?php } endforeach; } ?>
	</div>
    <div class="ibox-content">
        <h4>What are the possible ways of assessing student learning in the context of the lines of inquiry?  What evidence will we look for?</h4>
    <?php if(!empty($assessmentSpecificText)){
            foreach ($assessmentSpecificText as $evidenceDescription):
    ?>
            <p><?= $evidenceDescription ?></p>
    <?php  endforeach; } ?>
    </div>
</div>