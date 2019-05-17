

<div class="row" style="margin-bottom: 30px;">
    <div class="col-md-4">
        <a href="<?= base_url('academic/semester-antara'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list</a>
    </div>
    <div class="col-md-4" style="border: 1px solid #9E9E9E;text-align: center;background: #f1f1f1;padding: 15px;">
        <h3 style="margin-top: 7px;font-weight: bold;" id="SemesterName">-</h3>
    </div>
</div>


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li>
            <a href="<?php echo base_url('academic/semester-antara/timetable/'.$SASemesterID); ?>">Timetables</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/semester-antara/students/'.$SASemesterID); ?>">Students</a>
        </li>
        <li class="hide">
            <a href="<?php echo base_url('academic/semester-antara/score/'.$SASemesterID); ?>">Score</a>
        </li>



        <li>
            <a href="<?php echo base_url('academic/semester-antara/setting-timetable/'.$SASemesterID); ?>">Set Timetables</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/semester-antara/setting-exam/'.$SASemesterID); ?>">Set Exam</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/semester-antara/setting/'.$SASemesterID); ?>"><i class="fa fa-cog"></i></a>
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

        var dataSemester = JSON.parse($('#dataSemester').val());
        var SemesterName = (dataSemester.length>0) ? dataSemester[0].Name : '';
        $('#SemesterName').html(SemesterName);

        var menu_active = "<?php echo $this->uri->segment(3); ?>";
        var arrMenu = ['timetable','students','setting-timetable','setting-exam','setting'];
        setMenuSelected('.nav-tabs','li','active',arrMenu,menu_active);
    });

</script>