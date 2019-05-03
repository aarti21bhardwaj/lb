<div class="ibox ">
    <div class="ibox-title">
        <h3>5. What resources need to be gathered?</h3>
    </div>
    <?php if(!empty($data->unit_resources)){ 
    		foreach ($data->unit_resources as $resource) : ?>
		    <div class="ibox-content">
		        <h4><?= $resource->name ?></h4>
		        <?php if($resource->description){?>
		        	<p><?=$resource->description ?></p>
		        <?php }elseif ($resource->url){?>
		        	<p><?= $resource->url ?></p>
		        <?php }else{?>
		        	<p><?= $resource->image_url ?></p>
		        <?php }?>
		    </div>
	<?php endforeach; } ?>
</div>