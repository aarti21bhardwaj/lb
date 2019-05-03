<div class="ibox ">
    <div class="ibox-title">
        <h3>4. How best might we learn?</h3>
    </div>
    <div class="ibox-content">
    
    <?php if(!empty($assessmentSubtypes)){
            foreach ($assessmentSubtypes as $key => $subtype):               
    ?>
	    	<h4><?= $key ?></h4>
            <?php 
            if(!empty($subtype)){
                        foreach ($subtype as $key => $value) :
             ?>
                <p><?= $value->description ?></p>
            <?php endforeach; ?>
            <?php }?>	
    <?php endforeach;?>
    <?php }?>
    <br>
    <br>
    <?php if(!empty($data->unit_specific_contents)){ ?>

        <h4>Transdisciplinary Skills :</h4>
            <ul>
                <?php 
                        foreach ($data->unit_specific_contents as $unitSpecificContent):
                         if($unitSpecificContent->content_category->type == "transdisciplinary_skills"){ 
                ?>   
                <li>
                    <?= $unitSpecificContent->text; ?>
                </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
        <br>
        <h4>Learner Profile: :</h4>
            <ul>
                <?php 
                        foreach ($data->unit_specific_contents as $unitSpecificContent):
                         if($unitSpecificContent->content_category->type == "learner_profile"){ 
                ?>   
                <li>
                    <?= $unitSpecificContent->text; ?>
                </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
    <?php } ?>
	</div>
</div>