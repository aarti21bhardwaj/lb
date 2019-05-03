<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <?php if(!empty($activeTermCourseUnits)){?>
            <?php foreach ($activeTermCourseUnits as $term): ?>
                <div class="ibox-title">
                    <h3><b><?= h($term['name']) ?></b>&nbsp;-&nbsp;<?= h($term['division_name']) ?></h3>
                </div>
                <?php foreach ($term['courses'] as $course): ?>
                    <div class="ibox-title">
                        <h4><b><?= h($course['name']) ?></b></h4>
                    </div>
                    <div class = "ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables" >
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Unit Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($course['units'] as $key => $unit): ?>
                                       <tr>
                                           <td><?= $this->Number->format($key+1) ?></td>
                                           <td><?= h($unit['name']) ?></td>
                                       </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                   </div>
                <?php endforeach; ?>
                <br><br>
            <?php endforeach; ?>
            <?php }else{?>
                <div class="ibox-content">
                    <h2>All Units are archived for active terms</h2>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<style type="text/css">
    .footer{
        display: none;
    }
    /*#pageWrapper{
        height: 300px !important;
    }*/
</style>