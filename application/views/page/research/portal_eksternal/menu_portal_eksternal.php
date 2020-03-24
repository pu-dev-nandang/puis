<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(1)=='research' || $this->uri->segment(2)=='portal-eksternal') { echo 'active'; } ?>">
            <a href="<?php echo base_url('research/portal-eksternal'); ?>">Portal Eksternal</a>
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