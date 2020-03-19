<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!--=== Navigation ===-->
        <ul id="nav">
            <!-- list menu -->
            <li class="<?php if($this->uri->segment(1)=='ga_schedule_exchange'){echo "current";} ?>">
                <a href="<?php echo base_url('ga_schedule_exchange'); ?>">
                    <i class="fa fa-tachometer"></i>
                    Schedule Exchange
                </a>
            </li>
            <!-- ADDED BY FEBRI @ MARCH 2020 -->
            <li class="<?=(($this->uri->segment(2) == 'package-order') ? 'current' :'')?>">
                <a href="<?=base_url('general-affair/package-order'); ?>">
                    <i class="fa fa-archive"></i>
                    Package Order
                </a>
            </li>
            <!-- END ADDED BY FEBRI @ MARCH 2020 -->
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
