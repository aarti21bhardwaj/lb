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
<div class="row">
    <div class="col-md-6">
        <div class="ibox ">
            <div class="ibox-title">
                <h3>1. What is our purpose?</h3>
            </div>
            <div class="ibox-content">
                <?= $this->element('Pdf/our_purpose_pyp')?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox ">
            <?= $this->element('Pdf/what_we_want_to_learn_pyp')?>  
        </div>
        <div class="ibox ">
            <?= $this->element('Pdf/want_to_learn_pyp')?>
        </div>
    </div>
</div>
<a href=""></a>>
<div class="row">
    <div class="col-md-6">
        <?= $this->element('Pdf/what_we_have_learn_pyp')?>
    </div>
    <div class="col-md-6">
        <?= $this->element('Pdf/best_might_we_learn_pyp')?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->element('Pdf/unit_resources_pyp')?>
    </div>
</div>
<a href=""></a>>
<?= $this->element('Pdf/unit_reflections_pyp')?>
<style type="text/css">

    @media print {  
        p h1 h2 h3 {
            font-size:10px;
        }
        @page {
              size: letter landscape;
              margin: 0;
            }

/*        hr {page-break-after: always;}*/
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
<script type="text/javascript">
    $(document).ready(function(){
        window.print();
    })

</script>
<?php }else{ ?>
    <div class="ibox-content col-sm-offset-1">
        <div class="ibox-title text-center">
            <h2>No Unit has been found</h2>
        </div>
    </div>
<?php } ?>