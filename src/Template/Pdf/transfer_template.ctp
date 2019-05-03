<?php
/**
  * @var \App\View\AppView $this
  */
namespace App\Controller;
use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;

?>
<?php if($data){?>
<div class = 'row'>
    <div class = 'col-lg-12'>
        <div class="ibox float-e-margins">
            <div class="ibox-content col-sm-offset-1">
                <div class="ibox-title text-center">
                    <h2><?= h(strtoupper($data->name)); ?></h2>
                </div>
                <div class="row">
                    <div class = "ibox-content">
                        <h3><?= h("META UNIT"); ?></h3>
                        <br>
                        <div class = "ibox-content">
                            <?= $this->element('Pdf/transfer_heading')?>
                        </div>
                    </div>
                    <div class = "ibox-content">
                        <h3><?= h("ESSENTIAL ELEMENTS OF SUCCESS"); ?></h3>
                        <?= $this->element('Pdf/essential_elements_transfer')?>
                    </div>
                    <div class = "ibox-content">
                        <h3><?= h("RESOURCES AND REFLECTIONS"); ?></h3>
                        <br>
                        <div class = "ibox-content">
                            <?= $this->element('Pdf/resources')?>
                            <?= $this->element('Pdf/reflections')?>
                        </div>
                    </div>
                    <div class = "ibox-content">
                        <div class="ibox-title text-center">
                            <h2><?= h('ASSESSMENTS'); ?></h2>
                        </div>
                        <div class = "ibox-content">
                            <h3><b>TRANSFER / PERFORMANCE TASK </b></h3>
                            <?= $this->element('Pdf/performance_task_assessment')?>
                            <h3><b>LEARNING EXPERIENCE </b></h3>
                            <?= $this->element('Pdf/learning_experience_transfer_assessment')?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

<script type="text/javascript">
    $(document).ready(function(){
        window.print();
    })
</script>
<style type="text/css">

    @media print {  
        p h1 h2 h3 {
            font-size:10px;
        }
        @page {
              size: A4 portrait;
              margin: 0;
            }

        hr {page-break-after: always;}
        .row {
            margin-right: -15px;
            margin-left: -15px;
            width: 100%;
        }

        li{
          margin: 5px 0;
        }

        .wrapper {
            width:100%;
        }

        #wrapper {
            width:100%;   
        }
        div {
            display: block;
        }
        .col-md-6 {
            width: 50%;
            float: left;
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        footer {
            position: fixed;
            bottom: 0;
             font-size: 9px;
            color: #f00;
            text-align: center;
          }
        header {
            position: fixed;
            top: 0;
          }
        body {
            height: 100%;
            width: 100%;
            display: block;
            margin: 8px;
            font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #2f4050;
            font-size: 13px;
            color: #676a6c;
            overflow-x: hidden;
        }

        .gray-bg {
            background-color: #f3f3f4;
        }

    }

</style>

<?php }else{ ?>
    <div class="ibox-content col-sm-offset-1">
        <div class="ibox-title text-center">
            <h2>No Unit has been found</h2>
        </div>
    </div>
<?php } ?>