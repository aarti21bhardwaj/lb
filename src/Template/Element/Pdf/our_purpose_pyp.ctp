<h4>To inquire into the following :</h4>
<br>
<u><h4>Transdisciplinary Theme</h4></u>
<?php if($data->trans_disciplinary_theme){?>
    <h4 style="display:inline"><?= $data->trans_disciplinary_theme->name ?> : </h4>
    <?php if($data->trans_disciplinary_theme->description){?> 
        <p style="display:inline">
            <?= $data->trans_disciplinary_theme->description ?>    
        </p>
    <?php }?>
<?php } ?>
<br>
<br>
<h4>Central Idea : </h4>
<?php 
    if(!empty($data->unit_specific_contents)){
        foreach ($data->unit_specific_contents as $unitSpecificContent):
         if($unitSpecificContent->content_category->type == "central_idea"){ 
?>   
<p>
    <?= $unitSpecificContent->text; ?>
</p>
<?php } ?>
<?php endforeach; } ?>
<br>
<br>
<?php 
            if(!empty($assessmentTypes)){
            foreach ($assessmentTypes as $key => $assessmentType): 
                if($key == "Formative Assessments" || $key == "Summative Assessments"){
    ?>
    <h4><?= $key?> : </h4>
    <?php 
            if(!empty($assessmentType)){
                foreach ($assessmentType as $key => $value) :
     ?>
        <p><?= $value->description ?></p>
        <br>
    <?php endforeach; ?>
    <?php }?>
    <?php }?>
<?php endforeach; ?>
<?php }?>