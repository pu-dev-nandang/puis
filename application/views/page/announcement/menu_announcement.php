
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(2)=='list-announcement' || $this->uri->segment(2)=='detail-announcement') { echo 'active'; } ?>">
            <a href="<?php echo base_url('announcement/list-announcement'); ?>">List Announcement</a>
        </li>
        <li class="<?php if($this->uri->segment(2)=='create-announcement') { echo 'active'; } ?>">
            <a href="<?php echo base_url('announcement/create-announcement'); ?>">Create Announcement</a>
        </li>
<!--        <li class="--><?php //if($this->uri->segment(2)=='file-attachment') { echo 'active'; } ?><!--">-->
<!--            <a href="--><?php //echo base_url('announcement/list-announcement'); ?><!--">File Attachment</a>-->
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