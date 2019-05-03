<?php
/**
  * @var \App\View\AppView $this
  */
?>

<!-- Include Editor style. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<!-- Include Editor JS files. -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/froala_editor.pkgd.min.js"></script>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="reportTemplatePages form large-9 medium-8 columns content">
    <?= $this->Form->create($reportTemplatePage) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Edit Report Template Page') ?></legend>
        </div>
        <?php
            echo $this->Form->control('report_template_type_id', ['options' => $reportTemplateTypes, 'disable' => true]);
            echo $this->Form->control('title');
            // echo $this->Form->control('body');
        ?>
    </fieldset>
    <div class="form-group">
            <?= $this->Form->label('body', __('Body'), ['class' => ['col-sm-2', 'control-label']]) ?>
            <div class="col-sm-10">
                <?= $this->Form->input('body', ['type'=> 'textarea', 'label' => false, 'class' => ['form-control', 'fr-view'], 'required' => 'true']); ?>
            </div>
        </div>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div> <!-- .ibox-content ends --> 
		</div> <!-- .ibox ends -->
	</div> <!-- .col-lg-12 ends -->
</div> <!-- .row ends -->

<!-- Initialize the editor. -->
    <script>
        var rtpx;
        var host = $('#baseUrl').val();
        console.log($('select[id = report-template-type-id]').val());
        $(document).ready(function(){
            var id = $('select[id = report-template-type-id]').val();
            var variables = <?php echo json_encode($reportTemplateVariables); ?>;
            rtpx = variables[id];
            console.log(rtpx);
            updateFroala();
        });

        $('select[id = report-template-type-id]').on('change', function(){
            var templateTypeId = $(this).val();
            var templateVariables = <?php echo json_encode($reportTemplateVariables); ?>;
            rtpx = templateVariables[templateTypeId];
            console.log(rtpx);
            updateFroala();
            console.log('in select option');
        });
        $.FroalaEditor.DefineIcon('my_dropdown', {NAME: 'cog'});
         function updateFroala(){
            console.log('function');
            $.FroalaEditor.RegisterCommand('my_dropdown', {
              title: 'Advanced options',
              type: 'dropdown',
              focus: false,
              undo: false,
              refreshAfterCallback: true,
              options: rtpx,
              callback: function (cmd, val) {
                console.log (val);
                var editorInstance = this;

                editorInstance.html.insert(val);
              },
              // Callback on refresh.
              refresh: function ($btn) {
             //   console.log ('do refresh');
              },
              // Callback on dropdown show.
              refreshOnShow: function ($btn, $dropdown) {
                // // console.log(this.options);
                //  console.log(rtpx);
                console.log ('do refresh when show');
              }
            });
            $('textarea').froalaEditor('destroy');
            $(function() { $('textarea').froalaEditor({
                toolbarButtons: ['bold', 'italic', 'formatBlock', 'undo', 'redo' , '|', 'underline', 'strikeThrough', 'subscript', 'superscript', 'outdent', 'indent', 'fontSize', 'fontFamily', 'align', 'clearFormatting', 'insertTable', 'html', 'insertImage', 'insertVideo','my_dropdown', 'color', 'print'],
                  "key": 'DLAHYKAJOEc1HQDUH==',
                   colorsBackground: [
                    '#15E67F', '#E3DE8C', '#D8A076', '#D83762', '#76B6D8', 'REMOVE',
                    '#1C7A90', '#249CB8', '#4ABED9', '#FBD75B', '#FBE571', '#FFFFFF'
                   ],
                   colorsDefaultTab: 'background',
                   colorsStep: 6,
                   colorsText: [
                    '#15E67F', '#E3DE8C', '#D8A076', '#D83762', '#76B6D8', 'REMOVE',
                    '#1C7A90', '#249CB8', '#4ABED9', '#FBD75B', '#FBE571', '#FFFFFF'
                   ],
                // Set the image upload parameter.
                imageUploadParam: 'image_param',
                // Set the image upload URL.
                imageUploadURL: host+'api/reports/uploadImage',
                // Set request type.
                imageUploadMethod: 'POST'
            }) 
          });
         }
    </script>