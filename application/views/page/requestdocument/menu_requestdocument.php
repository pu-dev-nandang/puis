
<div class="" style="margin-top: 30px;">

     <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
                <li class="<?php if($activeMenu=='requestdocument') { echo 'active';} ?>"><a href="<?php echo base_url('requestdocument'); ?>"><i class="fa fa-th-list right-margin" aria-hidden="true"></i> Lecturers</a></li>
                <li class="<?php if($this->uri->segment(1)=='add_request') { echo 'active'; } ?>">
                    <a href="<?php echo base_url('add_request'); ?>">Add Request Document</a>
                </li>

                
            </ul>
            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>
        </div>
    </div>

</div>



