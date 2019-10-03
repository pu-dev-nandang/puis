<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <ul id="nav">
            <li class="<?php if($this->uri->segment(1)=='dashboard'){echo "current";} ?>">
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-tachometer"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?php if($this->uri->segment(1)=='cooperation'  && $this->uri->segment(2)=='kerjasama-perguruan-tinggi' ){echo "current";} ?>">
                <a href="<?php echo base_url('cooperation/kerjasama-perguruan-tinggi'); ?>">
                    <i class="fa fa-user-circle"></i>
                    Kerja Sama Perguruan Tinggi
                </a>
            </li>
        </ul>
        <div class="sidebar-widget align-center">
            <div class="btn-group" data-toggle="buttons" id="theme-switcher">
                <label class="btn active">
                    <input type="radio" name="theme-switcher" data-theme="bright"><i class="fa fa-sun-o"></i> Bright
                </label>
                <label class="btn">
                    <input type="radio" name="theme-switcher" data-theme="dark"><i class="fa fa-moon-o"></i> Dark
                </label>
            </div>
        </div>

    </div>
    <div id="divider" class="resizeable"></div>
</div>
<!-- /Sidebar -->
