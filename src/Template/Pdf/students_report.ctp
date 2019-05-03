<div class = 'row'>
    <div class = 'col-lg-12'>
        <div class="ibox float-e-margins">
            <!-- <div class="ibox-content"> -->
                <div class="col-sm-2">        
                </div>
                <div class="col-sm-8">
                    <?php foreach ($data as $report):?>
                             <?= $report['body'] ?><br><br><br>
                    <?php endforeach; ?>
                </div>
                <div class="col-sm-2">        
                </div>
            <!-- </div> -->
        </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

<!-- <script type="text/javascript">
    $(document).ready(function(){
        window.print();
    })
</script> -->