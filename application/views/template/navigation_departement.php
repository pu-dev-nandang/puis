
<style>
    .project-switcher .project-list li.current a {
        background-color : rgb(8, 63, 136);
    }
</style>

<!--=== Project Switcher ===-->
<div id="project-switcher" class="container project-switcher">
    <div id="scrollbar">
        <div class="handle"></div>
    </div>

    <div id="frame">
        <ul class="project-list">
            <li class="departement <?php if($departement=='admission'){echo 'current';} ?>"
                division="10"
                data-dpt="admission">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/admission.png'); ?>"></span>
                    <span class="title">Admission</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='academic'){echo 'current';} ?>"
                division="6"
                data-dpt="academic">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/academic.png'); ?>"></span>
                    <span class="title">Academic</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='finance'){echo 'current';} ?>"
                division="9"
                data-dpt="finance">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/finance.png'); ?>"></span>
                    <span class="title">Finance</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='human-resources'){echo 'current';} ?>"
                division="13"
                data-dpt="human-resources">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/hr.png'); ?>"></span>
                    <span class="title">Human Resources</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='general-affair'){echo 'current';} ?>"
                division="8"
                data-dpt="general-affair">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/ga.png'); ?>"></span>
                    <span class="title">General Affair</span>
                </a>
            </li>
            <li class="departement1 <?php if($departement=='cooperation'){echo 'current';} ?>"
                division="7"
                data-dpt="cooperation">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/cooperation.png'); ?>"></span>
                    <span class="title">Cooperation</span>
                </a>
            </li>

            <li class="departement <?php if($departement=='student-life'){echo 'current';} ?>"
                division="16"
                data-dpt="student-life">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/student_life.png'); ?>"></span>
                    <span class="title">Student Life</span>
                </a>
            </li>


            <li class="departement <?php if($departement=='lpmi'){echo 'current';} ?>"
                division="3"
                data-dpt="lpmi">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/lpmi.png'); ?>"></span>
                    <span class="title">LPMI</span>
                </a>
            </li>

            <li class="departement <?php if($departement=='purchasing'){echo 'current';} ?>"
                division="4"
                data-dpt="purchasing">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/purchase.png'); ?>"></span>
                    <span class="title">Procurement</span>
                </a>
            </li>   
            <li class="departement <?php if($departement=='it'){echo 'current';} ?>"
                division="12"
                data-dpt="it">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/it.png'); ?>"></span>
                    <span class="title">IT</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='admin-prodi'){echo 'current';} ?>"
                division="15"
                data-dpt="admin-prodi">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/admin.png'); ?>"></span>
                    <span class="title">Prodi</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='rektorat'){echo 'current';} ?>"
                division="2"
                data-dpt="rektorat">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/rectorat.png'); ?>"></span>
                    <span class="title">Rektorat</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='library'){echo 'current';} ?>"
                division="11"
                data-dpt="library">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/library.png'); ?>"></span>
                    <span class="title">Library</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='admin-fakultas'){echo 'current';} ?>"
                division="34"
                data-dpt="admin-fakultas">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/faculty.png'); ?>"></span>
                    <span class="title">Faculty</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='other-division'){echo 'current';} ?>"
                division="36"
                data-dpt="other-division">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/other.png'); ?>"></span>
                    <span class="title">Other</span>
                </a>
            </li>
        </ul>
    </div> <!-- /#frame -->
</div> <!-- /#project-switcher -->
