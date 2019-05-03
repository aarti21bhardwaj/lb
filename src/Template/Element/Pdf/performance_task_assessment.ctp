<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;
?>
<div class = "ibox-content">
    <div class="table-responsive">
        <?php 
            $i = 1;
            if(!empty($data->assessments)){
            foreach ($data->assessments as $key => $assessment): ?>
            <?php if($assessment->assessment_type_id == 5){?>
            <u><h3>Assessment <?= $i ?></h3></u>
            <?php  $i++; ?>
            <div class = "ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <tr>
                        <th scope="row"><?= __('Title') ?></th>
                        <td><?= $assessment->name ?></td>
                    </tr>
                    <tr>
                    <tr>
                        <th scope="row"><?= __('Start Date') ?></th>
                        <td><?= h($assessment->start_date) ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('End Date') ?></th>
                        <td><?= h($assessment->end_date) ?></td>
                    </tr>
                </table>
            </div>  
            <div class="ibox-content">
                <h3>How might students demonstrate the transfer goal(s)? </h3>
                <br>
                <ul>
                    <?php
                    if(!empty($assessment->assessment_contents)){
                    foreach ($assessment->assessment_contents as $assessmentContent): 
                        if($assessmentContent->content_category->type == "common_transfer_goals"){?>
                            <li>
                                <?= $assessmentContent->content_value->text; ?>
                            </li>
                        <?php } ?>
                    <?php endforeach; }?>
                    <?php
                    if(!empty($assessment->assessment_specific_contents)){
                    foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent): 
                        if($assessmentSpecificContent->content_category->type == "common_transfer_goals"){?>s
                            <li>
                                <?= $assessmentContent->content_value->text; ?>
                            </li>
                        <?php } ?>
                    <?php endforeach; }?>
                </ul>
            </div>
            <div class="ibox-content">
                <h3>What are potential products possible to demonstrate success? </h3>
                <br>
                <div class="ibox-content">
                    <?=  ($assessment->description) ? $assessment->description : ''  ?> 
                </div>
            </div>
            <div class = "ibox-content">
                <h3><?= h('What learning goals will you include in your summative assessment criteria and feedback?'); ?></h3>
                <br>
                <?php 
                if(!empty($assessment->strands)){
                    foreach ($assessment->strands as $key => $strand):
                        if(!empty($strand)){ ?>
                            <div class="feed-activity-list">
                                <div class="feed-element">
                                    <div>
                                        <u><h4><?= $key ?></h4></u>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover dataTables" >
                                                <tbody>
                                                    <?php 
                                                        if(!empty($strand)){
                                                            foreach ($strand as $standard) : ?>
                                                                <tr>
                                                                    <td><?= h($standard) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endforeach; ?>
                <?php } ?>
            </div>
            <div class = "ibox-content">
                <h3><?= h('Impacts'); ?></h3>
                <br>
                <?php 
                    if(!empty($assessment->assessment_impacts)){
                    foreach ($assessment->assessment_impacts as $key => $assessmentImpacts): ?>
                    <div class="ibox-content">
                        <div class="feed-activity-list">
                            <div class="feed-element">
                                <div>
                                    <u><h4><?= $key ?></h4></u>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables" >
                                            <tbody>
                                                <?php foreach ($assessmentImpacts as $impact) : ?>
                                                    <tr>
                                                        <td><?= h($impact->impact->name) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; } ?>
            </div>
        <?php   
        $unitResources = TableRegistry::get('UnitResources');
        $assessmentResources = $unitResources->find()->where(['object_name' => 'assessment', 'object_identifier' => $assessment->id])->all()->toArray();

        ?>
        <div class="ibox-content">
            <h3><b>Assessment Resources :- </b></h3>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                        <thead>
                            <tr>
                                <th scope="col"><?= h('Resource Name') ?></th>
                                <th scope="col"><?= h('Resource Type') ?></th>
                                <th scope="col"><?= h('Description') ?></th>
                                <th scope="col"><?= h('Url') ?></th>
                                <th scope="col"><?= h('File Path') ?></th>
                                <th scope="col"><?= h('Date') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($assessmentResources)){?>
                            <?php foreach ($assessmentResources as $assessmentResource): 
                            ?>
                            <tr>
                                <td><?= h($assessmentResource->name) ?></td>
                                <td><?= h($assessmentResource->resource_type) ?></td>
                                <td><?= ($assessmentResource->description) ? $assessmentResource->description : '' ?></td>
                                <td><?= ($assessmentResource->url) ? $assessmentResource->url : '' ?></td>
                                <td><?= ($assessmentResource->file_path) ? $assessmentResource->image_url : '' ?></td>
                                <td><?= h($assessmentResource->created) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php }?>  
                    </tbody>
                </table>
            </div>
        </div>
    <?php }?>
<?php endforeach; }?>
</div>
</div>