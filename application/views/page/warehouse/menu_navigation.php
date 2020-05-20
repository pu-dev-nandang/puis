<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!--=== Navigation ===-->
        <ul id="nav">
            <!-- list menu -->
            <li class="<?php if($this->uri->segment(2)=='master-data'){echo "current";} ?>">
                <a href="<?php echo base_url('warehouse/master-data'); ?>">
                    <i class="fa fa-database"></i>
                    Master Data
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='good-receive'){echo "current";} ?>">
                <a href="<?php echo base_url('warehouse/good-receive'); ?>">
                    <i class="fa fa-cart-plus"></i>
                    Good Receive
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
