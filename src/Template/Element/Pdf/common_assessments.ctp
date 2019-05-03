<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;
?>
<h3><b>FORMATIVE/SUBMMATIVE ASSESSMENT </b></h3>
<div class = "ibox-content">
    <div class="table-responsive">
        <?php 
            $i = 1;
            foreach ($data->assessments as $assessment): ?>
            <?php if($assessment->assessment_type_id == 1 || $assessment->assessment_type_id == 2){ ?>
                <u><h3>Assessment <?= $i ?></h3></u>
            <?php  $i++; ?>
            <div class = "ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <tr>
                        <th scope="row"><?= __('Title') ?></th>
                        <td><?= $assessment->name ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Description') ?></th>
                        <td><?= ($assessment->description) ? $assessment->description : '' ?></td>
                    </tr>
                    <tr>
                        <?php if($assessment->assessment_type_id == 1){ ?>
                        <th scope="row"><?= __('Type') ?></th>
                        <td><?= h('Assessment FOR Learning ') ?></td>
                        <?php }elseif($assessment->assessment_type_id == 2){ ?>
                        <th scope="row"><?= __('Type') ?></th>
                        <td><?= h('Assessment OF Learning ') ?></td>
                        <?php } ?>
                    </tr>
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
            <div class = "ibox-content">
            <h3><?= h('Unit Goal Alignment :-'); ?></h3>
            <br>
                <div class = "ibox-content">
                    <h3><?= h('Standards'); ?></h3>
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
                    <?php foreach ($assessment->assessment_impacts as $key => $assessmentImpacts): ?>
                        <div class="feed-activity-list">
                            <div class="feed-element">
                                <div>
                                    <h4><?= $key ?></h4>
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
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if(!empty($assessment->assessment_contents)){ ?>
            <div class="ibox-content">
                <h3>What do you want the students to be able to do at the end of this unit? </h3>
                <br>
                <ul>
                    <?php
                    
                    foreach ($assessment->assessment_contents as $assessmentContent): 
                        if($assessmentContent->content_category->type == "common_transfer_goals"){?>
                            <?php if($assessmentContent->content_value){ ?>
                                <li>
                                    <?= $assessmentContent->content_value->text; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php } ?>
            <?php if(!empty($assessment->assessment_specific_contents)){ ?>
            <div class="ibox-content">
                <h3>Enduring Understanding</h3>
                <br>
                <ul>
                    <?php
                    
                    foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent): 
                        if($assessmentSpecificContent->content_category->type == "enduring_undertandings"){?>
                            <?php if($assessmentSpecificContent->description){ ?>
                                <li>
                                    <?= $assessmentSpecificContent->description; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ibox-content">
                <h3>Essential Questions</h3>
                <br>
                <ul>
                    <?php
                    
                    foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent): 
                        if($assessmentSpecificContent->content_category->type == "essential_questions"){?>
                            <?php if($assessmentSpecificContent->description){ ?>
                                <li>
                                    <?= $assessmentSpecificContent->description; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ibox-content">
                <h3>Knowledge</h3>
                <br>
                <ul>
                    <?php
                    
                    foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent): 
                        if($assessmentSpecificContent->content_category->type == "knowledge"){?>
                            <?php if($assessmentSpecificContent->description){ ?>
                                <li>
                                    <?= $assessmentSpecificContent->description; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ibox-content">
                <h3>Knowledge</h3>
                <br>
                <ul>
                    <?php
                    
                    foreach ($assessment->assessment_specific_contents as $assessmentSpecificContent): 
                        if($assessmentSpecificContent->content_category->type == "skills"){?>
                            <?php if($assessmentSpecificContent->description){ ?>
                                <li>
                                    <?= $assessmentSpecificContent->description; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php } ?>
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
        <?php } ?>
<?php endforeach; ?>
</div>
</div>