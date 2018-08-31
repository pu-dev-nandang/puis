

<?php

$btn_list = "btn-default btn-default-success";
$btn_set_exam = "btn-default btn-default-success";
if($this->uri->segment(3)=='list-exam') {
    $btn_list = "btn-success";
} else if($this->uri->segment(3)=='set-exam-schedule') {
    $btn_set_exam = "btn-success";
}

?>

<div class="row" style="margin-top: 30px;">

    <div class="col-md-8 col-md-offset-4" style="text-align: right;">
        <a href="<?php echo base_url('academic/exam-schedule/list-exam'); ?>" class="btn <?php echo $btn_list; ?>">
            <i class="fa fa-calendar right-margin" aria-hidden="true"></i> List Schedule</a> |
        <a href="<?php echo base_url('academic/exam-schedule/set-exam-schedule'); ?>" class="btn <?php echo $btn_set_exam; ?>">
            <i class="fa fa-pencil right-margin" aria-hidden="true"></i> Set Exam Schedule
        </a>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <hr/>
        <?php echo $page; ?>
    </div>
</div>

