<?php
/**
  * @var \App\View\AppView $this
  */
use Cake\Collection\Collection;
?>
<h3><b>Unit Reflections: </b></h3>
<div class = "ibox-content">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTables" >
            <thead>
                <tr>
                    <th class='col-lg-6' scope="col"><?= h('Reflection') ?></th>
                    <th scope="col"><?= h('By') ?></th>
                    <th scope="col"><?= h('Date') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data->unit_reflections as $unitReflection):
                ?>
                <tr>
                    <td style="word-break: break-all;"><?= $unitReflection->description ?></td>
                    <td><?= h(($unitReflection->user) ? $unitReflection->user->first_name.' '.$unitReflection->user->last_name : '') ?></td>
                    <td><?= $unitReflection->created ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>