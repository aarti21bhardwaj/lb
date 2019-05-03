 <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="campusSettings form large-9 medium-8 columns content">
    <?= $this->Form->create($campusSetting) ?>
    <fieldset>
        <?php
            echo $this->Form->control('campus_id', ['label' => 'Choose Campus', 'options' => $campuses, 'empty'=> '--Please Select a Campus--','value' => '']);
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?= $this->Form->create(null, ['class' => 'form-horizontal']) ?>
                <?php foreach ($settingKeys as $key => $settingkey): ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $settingkey?></td>
                            <td>
                            <div class = 'form-group'>
                            <?php    
                            echo $this->Form->control('scale_id', ['options' => $scales, 'empty'=> '--Please Select a Scale--','value' => '']);                                  
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