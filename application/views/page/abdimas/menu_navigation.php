<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!--=== Navigation ===-->
        <ul id="nav">
            <!-- list menu -->
            <?php //if ($_SERVER['SERVER_NAME'] == 'localhost'): ?>
                <li class="<?php if($this->uri->segment(2)=='monitoring-abdimas'){echo"current";}?>">
                    <a href="<?php echo base_url('abdimas/monitoring-abdimas');?>">
                        <i class="fa fa-archive"></i>
                        Monitoring Community Services
                    </a>
                </li>
            <?php //endif ?>
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
