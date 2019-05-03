<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> <link rel="stylesheet" href="https://wfolly.firebaseapp.com/node_modules/sweetalert/dist/sweetalert.css">

<?php

$template = [
        'button' => '<button class="btn btn-w-m btn-primary" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($template);
$arrayLength = sizeof($students);
if($reportTemplate->report_template_email_setting->live_mode == 1){
	$mode = "Live Mode";
}else{
	$mode = "Test Mode";
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
	        <div  class = 'ibox-title'>
	          <div style="display:inline">
	              <h3 style="display:inline">Sending Emails for <?= $reportTemplate->name ?> on <?= $mode ?></h3>
	          </div>
	        </div>
        	<div class = "ibox-content">
				<div class="progress progress-striped active">
				    <div id='emailSend' style="width: 0.5%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" role="progressbar" class="progress-bar progress-bar-danger">
				    
				    </div>
				</div>
				<h3><span id='email_sent'>0</span> out of <?= $arrayLength?> emails sent.</h3>

				<div class="row">
                    <div class="col-sm-4 col-sm-offset-4">
                        <?= $this->Form->button(__('Send Emails'), ['class' => ['btn', 'btn-primary'], 'id' => 'sendEmails']) ?>
                    </div>
                </div> 
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

function recursiveSend(studentArray, i,reportTemplateId){
console.log(studentArray);	
console.log(i);
console.log(studentArray.length);

//Continue recursion
	$('#email_sent').html(i);
	if(i< studentArray.length){
		var studentId = studentArray[i]; 
	    var host = $('#baseUrl').val();
	    var count = i+1;
		//Fire ajax call
console.log('here');
		jQuery.ajax({
			        type: "GET",
			        url: host+"api/teachers/sendEmail/"+studentId+'/'+reportTemplateId,
			        headers:{"accept":"application/json"},
			        success: function (result) {

			        	count++;
				        console.log('in success');
						var percentage = (count/studentArray.length)*100;
						i++;
				       	console.log(percentage);
						$("#emailSend").width(percentage+'%');
						recursiveSend(studentArray, i, reportTemplateId);		    	
			        },
			        error: function(result){
			        	console.log(result);
			        	i++;
			        	recursiveSend(studentArray,i, reportTemplateId);
			        	return;
			        }
			    });
	} else {
	console.log('here2');
		return false;
	}
}

$(document).ready(function(){

	$('#sendEmails').click(function(){
		var disable;
		$(this).attr("disabled", true);

		swal({
            title: "Are you sure you want to send emails?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ok",
            cancelButtonText : "Cancel",
            closeOnConfirm: true
        }, function(isConfirm){
              if(isConfirm) {
				var studentsArray = <?php echo json_encode($students); ?>;
				var reportTemplateId = <?php echo json_encode($reportTemplate->id); ?>;
				var totalCount = studentsArray.length;
				var completed = 0;
				
				var params = {};
				var count = 0;
				totalLength = studentsArray.length;
				disable = recursiveSend(studentsArray,0,reportTemplateId);
		      }
        });

	})	
});

</script>
