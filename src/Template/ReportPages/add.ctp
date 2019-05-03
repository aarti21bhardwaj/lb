<?php
/**
  * @var \App\View\AppView $this
  */
 // echo $this->Html->script("https://cdn.tinymce.com/4/tinymce.min.js", ['block' => true]);
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
				<div class="reportPages form large-9 medium-8 columns content">
    <?= $this->Form->create($reportPage) ?>
    <fieldset>
        <div class = 'ibox-title'>
            <legend><?= __('Add Report Page') ?></legend>
        </div>
        <?php
            echo $this->Form->control('title');
            // echo $this->Form->control('body');
        ?>
        <div class="form-group">
            <?= $this->Form->label('body', __('Body'), ['class' => ['col-sm-2', 'control-label']]) ?>
            <div class="col-sm-10">
                <?= $this->Form->input('body', ['type'=> 'textarea', 'label' => false, 'class' => ['form-control', 'fr-view'], 'required' => 'true']); ?>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div>

<!-- Initialize the editor. -->
<script type="text/javascript">
    var x = <?php echo json_encode($reportTemplateVariables); ?>;
    var host = $('#baseUrl').val();
    console.log(x);

    $.FroalaEditor.DefineIcon('my_dropdown', {NAME: 'cog'});
    $.FroalaEditor.RegisterCommand('my_dropdown', {
      title: 'Insert Variable',
      type: 'dropdown',
      focus: false,
      undo: false,
      refreshAfterCallback: true,
      options: x,
      callback: function (cmd, val) {
        console.log (val);
        var editorInstance = this;

        editorInstance.html.insert(val);
      },
      // Callback on refresh.
      refresh: function ($btn) {
        console.log ('do refresh');
      },
      // Callback on dropdown show.
      refreshOnShow: function ($btn, $dropdown) {
        console.log ('do refresh when show');
      }
    });

   
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

</script>