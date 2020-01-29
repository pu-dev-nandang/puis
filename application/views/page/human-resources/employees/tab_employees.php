

<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?= ($this->uri->segment(2)=='employees' && $this->uri->segment(3)=='') ? 'active' : ''; ?>">
            <a href="<?= base_url('human-resources/employees'); ?>">List Employees</a>
        </li>
        <li class="<?php if($this->uri->segment(3) == 'input-employees') { echo 'active'; } ?>">
            <a href="<?= base_url('human-resources/employees/input-employees'); ?>">Add Employees</a>
        </li>
        <li class="<?php if($this->uri->segment(3) == 'preferences') { echo 'active'; } ?>">
            <a href="<?= base_url('human-resources/employees/preferences'); ?>">Preferences</a>
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
