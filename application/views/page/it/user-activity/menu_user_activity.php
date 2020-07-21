
<div class="" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
                <li class="<?php echo ($this->uri->segment(2) == 'user-activity' &&  ($this->uri->segment(3) == '' || $this->uri->segment(3) == null)  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/user-activity'); ?>">P-Camp</a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'user-activity-lecturer'  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/user-activity-lecturer'); ?>">Lecturer</a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'user-activity-student'  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/user-activity-student'); ?>">Student</a>
                </li>
                <li class="hide <?php echo ($this->uri->segment(2) == 'user-activity-log-login'  ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/user-activity-log-login'); ?>">Log Login</a>
                </li>
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>



