
<?php

    $arr_menu = ['','course-offer','batal-tambah'];

?>

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if(in_array($this->uri->segment(3),$arr_menu)) { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/study-planning'); ?>">List Student</a>
        </li>
        <li class="<?php if($this->uri->segment(3) == 'outstanding') { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/study-planning/outstanding'); ?>">Monitoring Outstanding Payment</a>
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