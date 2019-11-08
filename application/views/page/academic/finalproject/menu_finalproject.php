

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
<!--        <li class="--><?php //if($this->uri->segment(3)=='list-student') { echo 'active'; } ?><!--">-->
<!--            <a href="--><?php //echo base_url('academic/final-project/list-student'); ?><!--">Final Project</a>-->
<!--        </li>-->
        <li class="<?php if($this->uri->segment(3)=='') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/final-project'); ?>">List Student</a>
        </li>


        <li class="<?php if($this->uri->segment(3)=='mentor-final-project') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/final-project/mentor-final-project'); ?>">Mentor Final Project</a>
        </li>


        <li class="<?php if($this->uri->segment(3)=='judiciums') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/final-project/judiciums'); ?>">Judiciums List</a>
        </li>

<!--        <li class="--><?php //if($this->uri->segment(3)=='seminar-schedule') { echo 'active'; } ?><!--">-->
<!--            <a href="--><?php //echo base_url('academic/final-project/seminar-schedule'); ?><!--">Seminar Schedule</a>-->
<!--        </li>-->
<!--        <li class="--><?php //if($this->uri->segment(3)=='scheduling-final-project') { echo 'active'; } ?><!--">-->
<!--            <a href="--><?php //echo base_url('academic/final-project/scheduling-final-project'); ?><!--">Set Schedule</a>-->
<!--        </li>-->

    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>