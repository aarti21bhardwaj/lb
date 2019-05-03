<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($reportTemplateType->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplateType->id) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
        <h4><?= __('Related Report Template Pages') ?></h4>
        </div>
        <?php if (!empty($reportTemplateType->report_template_pages)): ?>
        <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables" >
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Report Template Type Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Body') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reportTemplateType->report_template_pages as $reportTemplatePages): ?>
            <tr>
                <td><?= h($reportTemplatePages->id) ?></td>
                <td><?= h($reportTemplatePages->report_template_type_id) ?></td>
                <td><?= h($reportTemplatePages->title) ?></td>
                <td><?= h($reportTemplatePages->body) ?></td>
                <td class="actions">
                    <?= '<a href='.$this->Url->build(['controller' => 'ReportTemplatePages','action' => 'view', $reportTemplateType->id]).' class="btn btn-xs btn-success">' ?>
                        <i class="fa fa-eye fa-fw"></i>
                    </a>
                    <?= '<a href='.$this->Url->build(['controller' => 'ReportTemplatePages','action' => 'edit', $reportTemplateType->id]).' class="btn btn-xs btn-warning"">' ?>
                        <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <?= $this->Form->postLink(__(''), ['controller' => 'ReportTemplatePages','action' => 'delete', $reportTemplateType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateType->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
        </div><!-- .ibox-content end -->
        <?php endif; ?>
        </div><!-- ibox end-->
    </div><!-- .col-lg-12 end-->
    </div><!-- .row end-->

<!-- </div> -->

