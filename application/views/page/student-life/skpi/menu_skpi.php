


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(2)=='skpi' && $this->uri->segment(3)=='') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/skpi'); ?>">SKPI</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='judiciums-monitoring') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/skpi/judiciums-monitoring'); ?>">Judiciums Monitoring</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">

        <?php
        $ServerName = $_SERVER['SERVER_NAME'];
        if($ServerName=='localhost'){
            echo $page;

        } else {
            echo "<h3>This module is in the process of being developed by the IT Team <i class='fa fa-smile-o'></i> <i class='fa fa-smile-o'></i> <i class='fa fa-smile-o'></i>
                            <br/><small>we make it with <i class='fa fa-coffee'></i> and <i style='color: #ff00008c;' class='fa fa-heart'></i></small></h3>";
        }


        ?>

    </div>
</div>