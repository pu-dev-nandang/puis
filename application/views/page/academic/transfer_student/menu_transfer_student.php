

<?php $arrMenu = ['transfer-student','programme-study','course-conversion']; ?>

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if(in_array($this->uri->segment(2),$arrMenu)) { echo 'active'; } ?>">
            <a href="<?php echo base_url('academic/transfer-student/programme-study'); ?>">Transfer Programme Study</a>
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