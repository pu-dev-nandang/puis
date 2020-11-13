


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="btn-primary <?php if($this->uri->segment(3)=='') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/list-podivers'); ?>">List Podivers</a>
        </li>
        <!-- <li class="<?php if($this->uri->segment(3)=='set-group') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/list-podivers/set-group'); ?>">Set Group Access</a>
        </li>   -->           

    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>
