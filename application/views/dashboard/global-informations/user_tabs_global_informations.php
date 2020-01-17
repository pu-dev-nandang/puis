<div id="global-informations">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabbable tabbable-custom tabbable-full-width">
                    <ul class="nav nav-tabs">
                        <li class="<?=($this->uri->segment(2)=='students') ? 'active':''?>">
                            <a href="<?=site_url('global-informations/students'); ?>">Students</a>
                        </li>
                        <li class="<?=($this->uri->segment(2)=='lecturers') ? 'active':''?>">
                            <a href="<?=site_url('global-informations/lecturers'); ?>">Lecturers</a>
                        </li>
                        <li class="<?=($this->uri->segment(2)=='employees') ? 'active':''?>">
                            <a href="<?=site_url('global-informations/employees'); ?>">Employees</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?=$page?>
            </div>
        </div>
    </div>
</div>
