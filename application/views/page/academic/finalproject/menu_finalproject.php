

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(3)=='list-student') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/final-project/list-student'); ?>">Final Project</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='monitoring-yudisium') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/final-project/monitoring-yudisium'); ?>">Monitoring Yudisium</a>
        </li>
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?php echo $page; ?>
            </div>
        </div>

    </div>
</div>