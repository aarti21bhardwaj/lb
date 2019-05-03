<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
?>
<h3><b>Unit Resources: </b></h3>
<div class="ibox-content">
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
                <?php foreach ($data->unit_resources as $unitResources):
                ?>
                    <tr>
                        <td><?= h($unitResources->name) ?></td>
                        <td><?= h($unitResources->resource_type) ?></td>
                        <td><?= ($unitResources->description) ? $unitResources->description : '' ?></td>
                        <td><?= ($unitResources->url) ? $unitResources->url : '' ?></td>
                        <td><?= ($unitResources->file_path) ? $unitResources->image_url : '' ?></td>
                        <td><?= h($unitResources->created) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>