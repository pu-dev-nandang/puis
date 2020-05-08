
<div class="" style="margin-top: 30px;">

    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(2); ?>
            <ul class="nav nav-tabs">
                <li class="<?php if($activeMenu=='students') { echo 'active';} ?>"><a href="<?php echo base_url('database/students'); ?>"><i class="fa fa-th-list right-margin" aria-hidden="true"></i> List Student</a></li>
                <li class="<?php if($activeMenu=='block-students') { echo 'active';} ?>"><a href="<?php echo base_url('database/block-students'); ?>"><i class="fa fa-minus-circle right-margin" aria-hidden="true"></i> Student Block</a></li>
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>


</div>



