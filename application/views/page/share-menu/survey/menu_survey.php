
<div class="" style="margin-top: 30px;">
    <div class="col-md-12">
        <div class="tabbable tabbable-custom tabbable-full-width">
            <?php $activeMenu = $this->uri->segment(1); ?>
            <ul class="nav nav-tabs">
                <li class="<?= ($this->uri->segment(2) == 'list-survey' ) ? 'active' : '' ?>">
                    <a href="<?php echo base_url('survey/list-survey'); ?>">List Survey</a>
                </li>
                <li class="<?= ($this->uri->segment(2) == 'create-survey') ? 'active' : '' ?>">
                    <a href="<?php echo base_url('survey/create-survey'); ?>">Create Survey</a>
                </li>
                <li class="<?= ($this->uri->segment(2) == 'bank-question') ? 'active' : '' ?>">
                    <a href="<?php echo base_url('survey/bank-question'); ?>">Bank Question</a>
                </li>
                <li class="<?= ($this->uri->segment(2) == 'create-question') ? 'active' : '' ?>">
                    <a href="<?php echo base_url('survey/create-question'); ?>">Create Question</a>
                </li>
            </ul>

            <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                <?php echo $page; ?>
            </div>

        </div>
    </div>
</div>





