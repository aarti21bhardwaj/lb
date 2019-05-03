<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
    <!-- <div class="terms index large-9 medium-8 columns content"> -->
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Terms') ?></h3>
            <div class="text-right">
            <?php 
                  $url = $this->Url->build('/terms/showArchiveUnits');
            ?>
            <button class="btn btn-primary" id="showUnits" onclick = <?= "openIframeModal('".$url."')" ?>>Archive & Rollover Units</button>
            </div>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('academic_year_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('division_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($terms as $term): ?>
                        <tr>
                                        <td><?= $this->Number->format($term->id) ?></td>
                                        <td><?= h($term->name) ?></td>
                                        <td><?= h($term->start_date) ?></td>
                                        <td><?= h($term->end_date) ?></td>
                                        <td><?= $term->has('academic_year') ? $this->Html->link($term->academic_year->name, ['controller' => 'AcademicYears', 'action' => 'view', $term->academic_year->id]) : '' ?></td>
                                        <td><?= h($term->created) ?></td>
                                        <td><?= h($term->modified) ?></td>
                                        <td><?= $term->has('division') ? $this->Html->link($term->division->name, ['controller' => 'Divisions', 'action' => 'view', $term->division->id]) : '' ?></td>
                                        <td><?= !empty($term->is_active) ? 'Active' : 'In-Active' ?></td>
<!--                                         <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $term->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $term->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $term->id], ['confirm' => __('Are you sure you want to delete # {0}?', $term->id)]) ?>
                            </td> -->
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $term->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $term->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $term->id], ['confirm' => __('Are you sure you want to delete # {0}?', $term->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                <?= '<a href='.$this->Url->build(['controller'=> 'ReportingPeriods','action' => 'add', $term->id]).' class="btn btn-xs btn-primary" title="Report Settings"><i class="fa fa-gears fa-fw"></i></a>' ?>
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- </div> -->
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->


<!-- modal window -->
<?php
    $modalTemplate = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate);

?>
<div class="modal inmodal" id="myModal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">List Of Units</h4>
                <span>The following units for the active terms will be archived when you click on the Archive Units button.</span>
            </div>
            <div class="modal-body">
                <!-- <div id="rendorData"></div> -->
                <iframe  style="zoom:0.60" width="99.6%" height="500" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?= $this->Html->link('Pdf Report', ['controller' => 'Terms', 'action' => 'showArchiveUnits', 1], ['class' => ['btn', 'btn-warning'], 'target' => '_blank']) ?>
                <?= $this->Form->button(__('Archive Units'), ['class' => ['btn', 'btn-primary'],'id'=>"openConfirmationModal"]) ?>
            </div>
        </div>
    </div>
</div>


<!-- confirmation modal to archive all units -->
<?php
    $modalTemplate2 = [
         'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
         'button' => '<button class="btn btn-primary" {{attrs}}>{{text}}</button>'
];

$this->Form->setTemplates($modalTemplate2);

?>

<div class="modal inmodal" id="confirmationModal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <i class="fa fa-exclamation-circle modal-icon"></i>
                <h4 class="modal-title">Are you sure?</h4><br>
                <h3 style="text-align: center"> You want to archive units of active terms</h3><br><br>
                <div style="text-align: center;">
                    <?= $this->Form->button(__('OK'), ['class' => ['btn', 'btn-success'],'id'=>"archiveAllUnits"]) ?>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
 var host = $('#baseUrl').val();
 function openIframeModal(url){
    console.log('here in iframe modal');
    console.log(url);
    var archiveUnits = <?php echo json_encode($activeTermCourseUnits); ?>;
    console.log(archiveUnits);
    if(archiveUnits.length != 0){
        $('iframe').attr("src", url);
        $('#myModal').modal('show'); 
    }else{
        alert('Units already archived for the active terms'); 
    }
  } 

  $('#openConfirmationModal').on('click', function(){
    $('#confirmationModal').modal('show');
  });


  $('#archiveAllUnits').on('click', function(){
        document.getElementById('archiveAllUnits').disabled = true;
        $.ajax({
                url: host+"terms/archiveUnits",
                headers:{"accept":"application/json"},
                dataType: 'json',
                type: "get",
                success:function(data){
                 console.log(data);
                 $('#confirmationModal').modal('hide');
                 alert('Units Archived Successsfully!');
                 $('#myModal').modal('hide');
                 window.location.reload(); 
                },
                error:function(data){
                  
                }
            });
  });
</script>
