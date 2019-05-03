<div class="ibox-title">
    <h5>2. What do we want to learn?</h5>
</div>
<div class="ibox-content">
    <?php if(!empty($data->unit_specific_contents)){ ?>

        <div class="p-sm">
            <h3>Key Concepts :</h3>
            <ul>
                <?php 
                        foreach ($data->unit_specific_contents as $unitSpecificContent):
                         if($unitSpecificContent->content_category->type == "key_concepts"){ 
                ?>   
                <li>
                    <?= $unitSpecificContent->text; ?>
                </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="p-sm">
            <h3>Related Concepts :</h3>
            <ul>
                <?php 
                        foreach ($data->unit_specific_contents as $unitSpecificContent):
                         if($unitSpecificContent->content_category->type == "related_concepts"){ 
                ?>   
                <li>
                    <?= $unitSpecificContent->text; ?>
                </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="p-sm">
            <h3>Lines of inquiry :</h3>
            <ul>
                <?php 
                        foreach ($data->unit_specific_contents as $unitSpecificContent):
                         if($unitSpecificContent->content_category->type == "lines_of_inquiry"){ 
                ?>   
                <li>
                    <?= $unitSpecificContent->text; ?>
                </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- <div class="p-sm">
            <h3>Teacher Questions :</h3>
            <ul>
            </ul>
        </div> -->
    <?php } ?>
</div>