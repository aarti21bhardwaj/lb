<?php
$cakeDescription =  'Unit';
?>
<!DOCTYPE html>
<html>
<head>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?php echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>

    <?= $this->Html->css('bootstrap.min.css', ['fullBase' => true]) ?>
    <?= $this->Html->css('font-awesome.min.css', ['fullBase' => true]) ?>
    <?= $this->Html->css('animate.css', ['fullBase' => true]) ?>
    <?= $this->Html->css('style.css', ['fullBase' => true]) ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
        <?= $this->Html->script('jquery-2.1.1', ['fullBase' => true]) ?>
</head>

<body class="gray-bg">
<?php if(isset($data) && $data){?>
  <header>
    This is header.
  </header>
<?php }?>
     <div id="wrapper">
        
            <?=  $this->Form->hidden('baseUrl',['id'=>'baseUrl','value'=>$this->Url->build('/', true)]); ?>
            <div class="wrapper" id="pageWrapper">
           
             <?= $this->fetch('content') ?>

         </div>

     </div>
<?php if(isset($data) && $data){?>
    <footer>
        This is the text that goes at the bottom of every page.
    </footer>
<?php }?>
  <!-- <style>
    footer {
      font-size: 9px;
      color: #f00;
      text-align: center;
    }

    header {
      font-size: 9px;
      color: #f00;
      text-align: center;
    }

    @page {
      size: A3 landscape;
      margin: 11mm 17mm 17mm 17mm;
    }

    @media print {
      footer {
        position: fixed;
        bottom: 0;
      }
    header {
        position: fixed;
        top: 0;
      }
      .content-block, p {
        page-break-inside: avoid;
      }

      html, body {
        width: 210mm;
        height: 297mm;
      }
    }
</style> -->

  <?= $this->Html->script('jquery-2.1.1') ?>
  <?= $this->Html->script('bootstrap.min') ?>
</body>

</html>