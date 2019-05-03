<div class = 'row'>
    <div class = 'col-lg-12'>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Report Template Type Name') ?></th>
            <td><?= h($reportTemplatePage->report_template_type->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($reportTemplatePage->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reportTemplatePage->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Body') ?></th>
            <td><?= h($reportTemplatePage->body) ?></td>
        </tr>
    </table> <!-- table end-->
    </div> <!-- ibox-content end -->
    </div> <!-- ibox end-->
    </div><!-- col-lg-12 end-->
</div> <!-- row end-->