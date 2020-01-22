


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(3)=='list-lecturer' || $this->uri->segment(3)=='') { echo 'active'; } ?>">
            <a href="<?php echo base_url('lpmi/lecturer-evaluation/list-lecturer'); ?>">List Lecturer</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='list-question' || $this->uri->segment(3)=='crud-question') { echo 'active'; } ?>">
            <a href="<?php echo base_url('lpmi/lecturer-evaluation/list-question'); ?>">Question</a>
        </li>
        <!--        <li class="--><?php //if($this->uri->segment(3)=='permanent-lecturer') { echo 'active'; } ?><!--">-->
        <!--            <a href="--><?php //echo base_url('human-resources/monitoring-attendance/permanent-lecturer'); ?><!--">Resume (Coming Soon)</a>-->
        <!--        </li>-->
        <li class="<?php if($this->uri->segment(3)=='download-result' || $this->uri->segment(3)=='download-result') { echo 'active'; } ?>">
            <a href="<?=site_url('lpmi/lecturer-evaluation/download-result')?>">Donwload Result</a>
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
