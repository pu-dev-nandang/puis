


<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(2)=='student-medical-record' && $this->uri->segment(3)=='') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/student-medical-record'); ?>">Student Medical Record</a>
        </li>
        <li class="<?php if($this->uri->segment(3)=='medical-history') { echo 'active'; } ?>">
            <a href="<?php echo base_url('student-life/student-medical-record/medical-history'); ?>">Medical History</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">

        <?php

//        echo $page;

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