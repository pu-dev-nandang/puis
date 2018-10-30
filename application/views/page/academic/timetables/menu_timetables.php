
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(3)=='list') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/timetables/list'); ?>">Timetables</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='course-offer') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/timetables/course-offer'); ?>">Course Offer</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='setting-timetable') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/timetables/setting-timetable'); ?>">Set Timetable</a>
        </li>
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12" style="margin-top: 30px;">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>