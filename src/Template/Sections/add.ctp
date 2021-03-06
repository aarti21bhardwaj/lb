<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="sections form large-9 medium-8 columns content">
                    <?= $this->Form->create(null) ?>
                    <fieldset>
                        <div class = 'ibox-title'>
                            <legend><?= __('Add Section') ?></legend>
                        </div>
                        <?php
                            echo $this->Form->control('name');
                            echo $this->Form->control('course_id', ['empty' => '--Please Select--', 'options' => $courses]);
                            echo $this->Form->control('term_id', ['empty' => '--Please Select--', 'options' => $terms]);
                            echo $this->Form->control('teacher_id', ['empty' => '--Please Select--','options' => $teachers]);
                        ?>
                    </fieldset>
                    <?= $this->Form->button(__('Submit')) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>