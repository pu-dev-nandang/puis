
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li>
            <a href="<?php echo base_url('academic/exam-schedule/list-exam'); ?>">List Exam Schedule</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/exam-schedule/list-waiting-approve'); ?>">List Waiting Approve</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/exam-schedule/set-exam-schedule'); ?>">Set Exam Schedule</a>
        </li>
        <li>
            <a href="<?php echo base_url('academic/exam-schedule/exam-setting'); ?>">Exam Setting</a>
        </li>

<!--        <li style="float: right;">-->
<!--            <a href="--><?php //echo base_url('academic/exam-schedule/exam-barcode'); ?><!--">Exam Barcode</a>-->
<!--        </li>-->
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <hr/>
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {

        var menu_active = "<?php echo $this->uri->segment(3); ?>";
        var arrMenu = ['list-exam','list-waiting-approve','set-exam-schedule','exam-setting','exam-barcode'];
        setMenuSelected('.nav-tabs','li','active',arrMenu,menu_active);

    });
</script>



