
<div class="" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
                <li class="<?= ($this->uri->segment(3) == 'list-eula') ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/eula/list-eula'); ?>">List</a>
                </li>
                <li class="<?= ($this->uri->segment(3) == 'create-eula' ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('it/eula/create-eula'); ?>">Create / Edit</a>
                </li>
            </ul>

            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>

        </div>
    </div>
</div>





