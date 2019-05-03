<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class = 'ibox-title'>
            <h3><?= __('Report Template Pages') ?></h3>
        </div>
        <div class = "ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables" >
                    <thead>
                        <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= h('Report Template Type Name') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportTemplatePages as $reportTemplatePage): ?>
                        <tr>
                                        <td><?= $this->Number->format($reportTemplatePage->id) ?></td>
                                        <td><?= h($reportTemplatePage->report_template_type->type) ?></td>
                                        <td><?= h($reportTemplatePage->title) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $reportTemplatePage->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $reportTemplatePage->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $reportTemplatePage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reportTemplatePage->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
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
