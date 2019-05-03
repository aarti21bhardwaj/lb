<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription =  $title.'| Application';
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
    <?= $this->Html->meta('icon') ?>

    <?php echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?php //$this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('animate.css') ?>
    <?= $this->Html->css('style.css') ?>
    <!-- Inspenia Switchery for toggle buttons -->
    <?= $this->Html->css(['plugins/switchery/switchery'])?>

    <?= $this->Html->css('plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') ?>
    
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- Bootstrap Tour -->
    <!-- <?= $this->Html->css(["plugins/bootstrapTour/bootstrap-tour.min"]) ?> -->
    <!-- <?= $this->Html->css("plugins/angular-flip-clock/angular-flip-clock") ?> -->

    <!-- Gritter -->
    <?= $this->Html->script('jquery-2.1.1') ?>

    <!-- <?= $this->Html->css('plugins/sweetalert/sweetalert') ?> -->
    <!-- <?= $this->Html->script('plugins/sweetalert/sweetalert.min') ?> -->

    <!-- <?= $this->Html->script('plugins/c3/c3.min.js') ?> -->
    <!-- <?= $this->Html->script('plugins/d3/d3.min.js') ?> -->
<!--     <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-route.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-cookies.js"></script> -->

<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
</head>

<body >
    <div id="wrapper">
        <div class="gray-bg">

            <?=  $this->Form->hidden('baseUrl',['id'=>'baseUrl','value'=>$this->Url->build('/', true)]); ?>
            <div class="wrapper wrapper-content animated fadeIn" id="pageWrapper">
             <?= $this->Flash->render('auth', ['element' => 'Flash/error']) ?>
             <?= $this->Flash->render() ?>

             <?= $this->fetch('content') ?>

            </div>
         <?= $this->element('footer'); ?>
     </div>

 </div>

 <!-- Scripts -->
 <!-- Metis Menu-->

 <?= $this->Html->script('bootstrap.min') ?>
  <?= $this->Html->script('jquery.cookie') ?> 
 <?= $this->Html->script(['/js/plugins/metisMenu/jquery.metisMenu', '/js/plugins/pace/pace.min.js', '/js/plugins/slimscroll/jquery.slimscroll.min', '/js/plugins/toastr/toastr.min', '/js/plugins/validator/validator.js', 'plugins/staps/jquery.steps.min']) ?> 


 <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/css/swiper.css"> -->
 <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/css/swiper.min.css">
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.js"></script> -->
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.min.js"></script>
 --> <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.jquery.js"></script> -->
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.jquery.min.js"></script> --> 

 <!-- Jquery UI Script-->
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?= $this->Html->script('select2.full.min') ?>

<?= $this->fetch('scriptBottom'); ?>
 
 <?= $this->Html->script('inspinia') ?>
</body>

</html>