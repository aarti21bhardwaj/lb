<?php
/**
  * @var \App\View\AppView $this
  */
?>
 <div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="campusSettings form large-9 medium-8 columns content">
    <?= $this->Form->create(empty($campusSettings) ? null: $campusSettings, ['class' => 'form-horizontal']) ?>
    <fieldset>
        <table class="table">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Description</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settingKeys as $key => $settingkey): ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $descriptions[$key] ?></td>
                            <td><?= $settingkey?></td>
                            <td>
                                <div class = 'form-group'>
                                <?php

                                if(isset($campusSettings[$key])){
                                    echo $this->Form->control($key.'.id', ['type' => 'hidden', 'value' => $campusSettings[$key]->id]);
                                }
                                echo $this->Form->control($key.'.campus_id', ['type' => 'hidden', 'value' => $campus->id]);
                                echo $this->Form->control($key.'.setting_key_id', ['type' => 'hidden', 'value' => $key]);
                                if($descriptions[$key] == 'Weightage'){
                                    echo $this->Form->input($key.'.value', ['class' => ['col-sm-2'], 'label' => false]);

                                }elseif($descriptions[$key] == 'Scale Id'){

                                    echo $this->Form->control($key.'.value', ['required', 'options' => $scales, 'empty'=> '--Please Select a Scale--', 'label' => false]);                              
                                }elseif($descriptions[$key] == 'File or Description'){
                                    echo $this->Form->control($key.'.value', ['required', 'options' => ['File' => 'File', 'Description' => 'Description'], 'empty'=> '--Please Select--', 'label' => false]);
                                }elseif ($descriptions[$key] == 'Add evidence for multiple courses or a single course.'){
                                    echo $this->Form->control($key.'.value', ['required', 'options' => ['False', 'True'], 'empty'=> '--Please Select--', 'label' => false]);
                                }
                                ?>
                                </div> 
                            </td>
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

			</div>
		</div>
	</div>
</div> 