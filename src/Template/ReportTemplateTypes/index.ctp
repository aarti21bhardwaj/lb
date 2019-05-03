<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Types') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplateTypes as $reportTemplateType): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplateType->id) ?></td>
                                        <td><?= h($reportTemplateType->type) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $reportTemplateType->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $reportTemplateType->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $reportTemplateType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplateType->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</div><!-- .ibox  end -->
</div><!-- .col-lg-12 end -->
</div><!-- .row end -->
