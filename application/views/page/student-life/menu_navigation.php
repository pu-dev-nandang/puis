<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">
<!--            <li class="--><?php //if($this->uri->segment(2)=='diploma-supplement'){echo"current";}?><!--">-->
<!--                <a href="--><?php //echo base_url('student-life/diploma-supplement/list-student');?><!--">-->
<!--                    <i class="fa fa-paperclip"></i>-->
<!--                    Diploma Supplement-->
<!--                </a>-->
<!--            </li>-->
            <li class="<?= ($this->uri->segment(2)=='master') ? 'current open' : ''?>">
                <a href="javascript:void(0);">
                    <i class="icon-edit"></i>
                    Data Master
                    <i class="arrow <?= ($this->uri->segment(2)=='master') ? 'icon-angle-down' : 'icon-angle-left'?>"></i></a>
                <ul class="sub-menu">
                    <li class="<?= ($this->uri->segment(3)=='company') ? "current open" : ""?>">
                        <a href="<?= base_url('student-life/master/company/list') ?>">
                            <i class="icon-angle-right"></i>
                            Company
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='skpi'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/skpi');?>">
                    <i class="fa fa-file"></i>
                    SKPI & Judiciums
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='student-achievement'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/student-achievement/list');?>">
                    <i class="fa fa-trophy"></i>
                    Student Achievement
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='tracer-alumni'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/tracer-alumni');?>">
                    <i class="fa fa-share-square"></i>
                    Tracer Alumni
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='student-medical-record'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/student-medical-record');?>">
                    <i class="fa fa-heartbeat"></i>
                    Student Medical Record
                </a>
            </li>
        </ul>
        <div class="sidebar-title">
            <span>PORTAL ALUMNI LIVE</span>
        </div>
        <ul id="nav">

            <li class="<?php if($this->uri->segment(2)=='banner'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/banner');?>">
                    <i class="fa fa-picture-o"></i>
                    Banner Slider
                </a>
            </li>
                       
            <li class="<?php if($this->uri->segment(2)=='event'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/event');?>">
                    <i class="fa fa-calendar"></i>
                    Event
                </a>
            </li>

            <li class="<?php if($this->uri->segment(2)=='about'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/about');?>">
                    <i class="fa fa-life-ring"></i>
                    About Us
                </a>
            </li> 

            <!-- <li class="<?php if($this->uri->segment(2)=='contact'){echo"current";}?>">
                <a href="<?php echo base_url('student-life/contact');?>">
                    <i class="fa fa-address-book-o"></i>
                    Contact Us
                </a>
            </li> -->


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
