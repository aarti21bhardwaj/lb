<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\App\Model\Entity\Scale $scale
  */
?>
<!-- <div class="scales view large-9 medium-8 columns content"> -->
<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h2><?= h($scale->name) ?></h2>
        </div> <!-- ibox-title end-->
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($scale->name) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Scale Values') ?></h4>
        </div>
        <?php if (!empty($scale->scale_values)): ?>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <tr>
                        <th scope="col"><?= __('Id') ?></th>
                        <th scope="col"><?= __('Name') ?></th>
                        <th scope="col"><?= __('Value') ?></th>
                        <th scope="col"><?= __('Sort Order') ?></th>
                        <th scope="col"><?= __('Numeric Value') ?></th>
                    </tr>
                    <?php foreach ($scale->scale_values as $scaleValues): ?>
                    <tr>
                        <td><?= h($scaleValues->id) ?></td>
                        <td><?= h($scaleValues->name) ?></td>
                        <td><?= h($scaleValues->value) ?></td>
                        <td><?= h($scaleValues->sort_order) ?></td>
                        <td><?= h($scaleValues->numeric_value) ?></td>

                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->

<!-- </div> -->

