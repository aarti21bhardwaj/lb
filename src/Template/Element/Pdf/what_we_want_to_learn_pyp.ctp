<div class="ibox-content"  id="step1">
    <div class="p-sm">
        <h3 style="display:inline">Class/grade:</h3> <p style="display:inline">Grade <?= $grades?></p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h3 style="display:inline">Age group:</h3> <p style="display:inline">TBD</p>
    </div>
    <div class="p-sm">
        <h3 style="display:inline">School:</h3> <p style="display:inline"><?= $schoolName?></p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h3 style="display:inline">School code:</h3> <p style="display:inline">TBD</p>
    </div>
    <div class="p-sm">
        <h3 style="display:inline">Title:</h3> <p style="display:inline"><?= $data->name?></p>
    </div>
    <div class="p-sm">
        <h3 style="display:inline">Teacher(s):</h3> <p style="display:inline">Mrs. Cypress, Ms. Crane, and Ms. Williams</p>
    </div>
    <div class="p-sm">
        <h3 style="display:inline">Date:</h3> <p style="display:inline"><?= $data->start_date .' - '.$data->end_date?></p>
    </div>
    <div class="p-sm">
        <h3 style="display:inline">Proposed duration:</h3> <p style="display:inline"><?= $proposedHours ?> hour(s)</p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <h3 style="display:inline">over number of weeks:</h3> <p style="display:inline">TBD</p>
    </div>
</div>