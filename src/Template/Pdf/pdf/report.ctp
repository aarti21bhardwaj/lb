<div class = 'row'>
    <div class = 'col-lg-12'>
        <?php foreach ($reports as $report):?>
            <?= $report['body'] ?><br><br><br>
        <?php endforeach; ?> 
    </div>
</div>

<style type="text/css">
    @media print{
        td {
          padding: 0 10px 0 10px !important;
        } 
    .new-page { page-break-before: always !important; }

	* {
  overflow: visible !important;
}
}
</style>
