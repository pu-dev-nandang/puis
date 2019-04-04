

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h3>Sok</h3>
    </div>
</div>


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li>
            <a href="<?php echo base_url('academic/semester-antara/timetable/'.$IDSASemester); ?>">Timetables</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/semester-antara/exam/'.$IDSASemester); ?>">Exam</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/semester-antara/score/'.$IDSASemester); ?>">Score</a>
        </li>


        <li style="float: right;">
            <a href="<?php echo base_url('academic/semester-antara/setting-exam/'.$IDSASemester); ?>">Set Exam</a>
        </li>
        <li style="float: right;">
            <a href="<?php echo base_url('academic/semester-antara/setting-timetable/'.$IDSASemester); ?>">Set Timetables</a>
        </li>
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <textarea class="hide" id="dataSemester"><?= $DataSemesterAntara; ?></textarea>

        <div class="row">
            <div class="col-md-12" style="margin-top: 30px;">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>

<script>

    $(document).ready(function () {
        var menu_active = "<?php echo $this->uri->segment(3); ?>";
        var arrMenu = ['timetable','exam','score','setting-exam','setting-timetable'];
        setMenuSelected('.nav-tabs','li','active',arrMenu,menu_active);
    });

</script>