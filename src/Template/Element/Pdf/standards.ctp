<?php
/**
  * @var \App\View\AppView $this
  */
	use Cake\Collection\Collection;
?>
<h3><b>Standards: </b></h3>
<div class="ibox-content">
	<?php 
        
        if(!empty($data->unit_strands)){
            foreach ($data->unit_strands as $key => $unitStrand):
    ?>

    <div class="feed-activity-list">
        <div class="feed-element">
            <div>
                <?php if($unitStrand){?>
                <u><h3><?= $key ?></h3></u>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables" >
                        <tbody>
                            <?php 
                                foreach ($unitStrand as $standard) : ?>
                            <tr>
                                <td><?= h($standard) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php 
            $unitStandards = []; 
            endforeach; 
    } ?>
</div>
