<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
?>
<h3><b>Impacts: </b></h3>
<div class="ibox-content">
	<?php foreach ($data->unit_impacts as $key => $unitImpact):
            $unitImpacts = (new Collection($unitImpact))->extract('impact.name')->toArray();
    ?>

    <div class="feed-activity-list">
        <div class="feed-element">
            <div>
                <u><h3><?= $key ?></h3></u>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                        <tbody>
                            <?php 
                                if(!empty($unitImpact)){
                                foreach ($unitImpact as $impact) : ?>
                            <tr>
                                <td><?= h($impact->impact->name) ?></td>
                            </tr>
                            <?php endforeach; }else{?>
                            <tr>
                                <td>NO IMPACT AVAILABLE FOR THIS IMPACT CATEGORY.</td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php $unitImpacts = []; 
    endforeach; ?>
</div>