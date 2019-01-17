<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">
            <li class="<?php if($this->uri->segment(2)=='master'){echo "current open";} ?> hide">
                <a href="javascript:void(0);">
                    <i class="fa fa-globe"></i>
                    Master
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "student" ){echo "current";} ?>">
                        <a href="<?php echo base_url('academic/master/student'); ?>">
                        <i class="icon-angle-right"></i>
                        Student
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='curriculum'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/curriculum'); ?>">
                    <i class="fa fa-university"></i>
                    Curriculum
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='courses'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/courses'); ?>">
                    <i class="fa fa-th-large"></i>
                    Courses
                </a>
            </li>


            <li class="<?php if($this->uri->segment(2)=='academic-year'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/academic-year'); ?>">
                    <i class="fa fa-calendar-check-o"></i>
                    Academic Year
                </a>
            </li>

            <li class="<?php if($this->uri->segment(2)=='semester-antara'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/semester-antara'); ?>">
                    <i class="fa fa-align-right"></i>
                    Semester Antara
                </a>
            </li>


            <li class="<?php if($this->uri->segment(2)=='transfer-student'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/transfer-student/programme-study'); ?>">
                    <i class="fa fa-exchange"></i>
                    Transfer Student
                </a>
            </li>

<!--            <li class="">-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-percent"></i>-->
<!--                    Beasiswa-->
<!--                </a>-->
<!--            </li>-->

<!--            <li class="--><?php //if($this->uri->segment(2)=='ketersediaan-dosen'){echo "current";} ?><!--">-->
<!--                <a href="--><?php //echo base_url('academic/ketersediaan-dosen'); ?><!--">-->
<!--                    <i class="fa fa-pencil-square-o"></i>-->
<!--                    Ketersediaan Dosen-->
<!--                </a>-->
<!--            </li>-->
            <li class="<?php if($this->uri->segment(2)=='references'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/references'); ?>">
                    <i class="fa fa-external-link-square"></i>
                    References
                </a>
            </li>

        </ul>
        <div class="sidebar-title">
            <span>Academic Transactions</span>
        </div>
        <ul id="nav">
<!--            <li class="--><?php //if($this->uri->segment(2)=='timetables'){echo "current";} ?><!--">-->
<!--                <a href="--><?php //echo base_url('academic/timetables'); ?><!--">-->
<!--                    <i class="fa fa-calendar"></i>-->
<!--                    Timetables-->
<!--                </a>-->
<!--            </li>-->

            <li class="<?php if($this->uri->segment(2)=='timetables'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/timetables/list'); ?>">
                    <i class="fa fa-calendar"></i>
                    Timetables
                </a>
            </li>


<!--            <li class="--><?php //if($this->uri->segment(2)=='study-planning'){echo "current";} ?><!--">-->
<!--                <a href="--><?php //echo base_url('academic/study-planning'); ?><!--">-->
<!--                    <i class="fa fa-edit"></i>-->
<!--                    Study Plan-->
<!--                </a>-->
<!--            </li>-->
            <li class="<?php if($this->uri->segment(2)=='study-planning'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/study-planning/list-student'); ?>">
                    <i class="fa fa-edit"></i>
                    Study Plan
                </a>
            </li>

            <li class="<?php if($this->uri->segment(2)=='attendance'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/attendance/input-attendace'); ?>">
                    <i class="fa fa-users"></i>
                    Attendance
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='exam-schedule'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/exam-schedule/list-exam'); ?>">
                    <i class="fa fa-sitemap"></i>
                    Exam Schedule
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='score'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/score') ?>">
                    <i class="fa fa-area-chart"></i>
                    Score
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='final-project'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/final-project'); ?>">
                    <i class="fa fa-flag"></i>
                    Final Project
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='transcript'){echo "current";} ?>">
                <a href="<?php echo base_url('academic/transcript') ?>">
                    <i class="fa fa-line-chart"></i>
                    Transcript
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

