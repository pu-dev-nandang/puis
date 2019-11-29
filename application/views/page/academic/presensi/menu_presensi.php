
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='details-attendace') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance'); ?>">Attendance</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='monitoring-attendace-lecturer') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance/monitoring-attendace-lecturer'); ?>">Monitoring Attd. Lecturer</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='monitoring-attendace-student') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance/monitoring-attendace-student'); ?>">Monitoring Attd. Student</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='monitoring-all-student') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance/monitoring-all-student'); ?>">Monitoring All Student</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='monitoring-schedule-exchange') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance/monitoring-schedule-exchange'); ?>">Monitoring Schedule Exchange</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='teach-load') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/attendance/teach-load'); ?>">Lecturer Teaching Recap</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>
