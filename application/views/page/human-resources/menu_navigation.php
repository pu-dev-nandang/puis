<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">


            <li class="<?php if($this->uri->segment(2)=='employees'){echo"current";}?>">
                <a href="<?php echo base_url('human-resources/employees');?>">
                    <i class="fa fa-users"></i>
                    Master Employees
                </a>
            </li>
<!--            <li class="--><?php //if($this->uri->segment(2)=='human-resources'){echo"current";}?><!--">-->
<!--                <a href="--><?php //echo base_url('human-resources/lecturers');?><!--">-->
<!--                    <i class="fa fa-download"></i>-->
<!--                    Master Dosen-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="">-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-download"></i>-->
<!--                    Presensi Dosen-->
<!--                </a>-->
<!--            </li>-->
            <li class="<?php if($this->uri->segment(2)=='monitoring-attendance'){echo"current";}?>">
                <a href="<?php echo base_url('human-resources/monitoring-attendance/with-range-date'); ?>">
                    <i class="fa fa-line-chart"></i>
                    Monitoring Attendance
                </a>
            </li>


            <li class="<?php if($this->uri->segment(2)=='academic_employees'){echo"current";}?>">
                <a href="<?php echo base_url('human-resources/academic_employees');?>">
                    <i class="fa fa-id-card"></i>
                    Master Academic
                </a>
            </li>

            <li class="<?php if($this->uri->segment(2)=='setting_academic_hrd'){echo"current";}?>">
                <a href="<?php echo base_url('human-resources/setting_academic_hrd');?>">
                    <i class="fa fa-cog"></i>
                    Setting Academic
                </a>
            </li>

            <!-- ADDED BY FEBRI @ FEB 2020 -->
            <li class="<?= ($this->uri->segment(2)=='master-aphris') ? 'current open' : ''?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-user-circle"></i>
                    Master Aphris
                    <i class="arrow <?= ($this->uri->segment(2)=='master-aphris') ? 'icon-angle-down' : 'icon-angle-left'?>"></i></a>
                    <?php $tablename = array("master_status","master_level","master_industry_type","master_company","master_marital_status","master_family_relations","master_kelompok_profesi"); ?>
                    <ul class="sub-menu">
                        <?php foreach ($tablename as $tb) { ?>
                        <li class="<?= ($this->uri->segment(3)==$tb) ? 'current' : ''?>">
                            <a href="<?=site_url('human-resources/master-aphris/'.$tb)?>">
                                <i class="icon-angle-right"></i>
                                <span style="text-transform:capitalize"><?php $expl = explode("master_", $tb); echo str_replace("_", " ", $expl[1]);?></span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="<?= ($this->uri->segment(3)=='structure-organization') ? 'current' : ''?>">
                            <a href="<?=site_url('human-resources/master-aphris/structure-organization')?>">
                                <i class="icon-angle-right"></i>
                                <span style="text-transform:capitalize">Structure Organization</span>
                            </a>
                        </li>
                        
                        <!-- <li class="<?= ($this->uri->segment(3)=='schedule-approval') ? 'current' : ''?>">
                            <a href="<?=site_url('human-resources/master-aphris/schedule-approval')?>">
                                <i class="icon-angle-right"></i>
                                <span style="text-transform:capitalize">Schedule Approve Profile</span>
                            </a>
                        </li> -->

                    </ul>
            </li>


            <li class="<?php if($this->uri->segment(2)=='attendance-temp'){echo"current";}?>">
                <a href="<?php echo base_url('human-resources/attendance-temp');?>">
                    <i class="fa fa-sign-in"></i>
                    Attendance Temporary
                </a>
            </li>
            <!-- END ADDED BY FEBRI @ FEB 2020 -->


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
