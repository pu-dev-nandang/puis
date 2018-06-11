
<div class="" style="margin-top: 30px;">

    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(2); ?>
            <ul class="nav nav-tabs">
                <li class="<?php if($activeMenu=='lecturers') { echo 'active';} ?>"><a href="<?php echo base_url('database/lecturers'); ?>"><i class="fa fa-th-list right-margin" aria-hidden="true"></i> Lecturers</a></li>
                <li class="<?php if($activeMenu=='mentor-academic') { echo 'active';} ?>"><a href="<?php echo base_url('database/mentor-academic'); ?>"><i class="fa fa-users right-margin" aria-hidden="true"></i> Penasehat Akademik</a></li>
                <li class="<?php if($activeMenu=='mentor-final-academic') { echo 'active';} ?>"><a href="<?php echo base_url('database/mentor-final-academic'); ?>" data-toggle="tab"><i class="fa fa-flag right-margin" aria-hidden="true"></i> Final Project</a></li>
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>



<!--    <div class="tabbable tabbable-custom tabbable-full-width" >-->

<!--        <ul class="nav nav-tabs">-->
<!--            <li class="--><?php //if($activeMenu=='lecturers') { echo 'active';} ?><!--"><a href="--><?php //echo base_url('database/lecturers'); ?><!--" data-toggle="tab">Lecturers</a></li>-->
<!--            <li class="--><?php //if($activeMenu=='mentor-academic') { echo 'active';} ?><!--"><a href="--><?php //echo base_url('database/mentor-academic'); ?><!--" data-toggle="tab"></a></li>-->
<!--            <li class="--><?php //if($activeMenu=='mentor-final-academic') { echo 'active';} ?><!--"><a href="--><?php //echo base_url('database/mentor-final-academic'); ?><!--" data-toggle="tab"><i class="fa fa-flag right-margin" aria-hidden="true"></i> Final Project</a></li>-->
<!--        </ul>-->
<!---->
<!--        <div class="tab-content row">-->
<!--        </div>-->
<!--    </div>-->
</div>



