
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
            <li class="departement <?php if($departement=='rektorat'){echo 'current';} ?>"
                division="2"
                data-dpt="rektorat">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/rectorat.png'); ?>"></span>
                    <span class="title">Rektorat</span>
                </a>
            </li>

            <li class="departement <?php if($departement=='secretariat-rectorate'){echo 'current';} ?>"
                division="41"
                data-dpt="secretariat-rectorate">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/sec-rec.png'); ?>"></span>
                    <span class="title">Sec Rectorate</span>
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
            <li class="departement <?php if($departement=='admission'){echo 'current';} ?>"
                division="10"
                data-dpt="admission">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/admission.png'); ?>"></span>
                    <span class="title">Admission</span>
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
                    <span class="title">HR</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='general-affair'){echo 'current';} ?>"
                division="8"
                data-dpt="general-affair">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/ga.png'); ?>"></span>
                    <span class="title">GA</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='warehouse'){echo 'current';} ?>"
                division="42"
                data-dpt="warehouse">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/warehouse.png'); ?>"></span>
                    <span class="title">Warehouse</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='cooperation'){echo 'current';} ?>"
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
                    <span class="title">Purchasing</span>
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
            <li class="departement <?php if($departement=='research'){echo 'current';} ?>"
                division="5"
                data-dpt="research">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/research.png'); ?>"></span>
                    <span class="title">Research</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='abdimas'){echo 'current';} ?>"
                division="38"
                data-dpt="abdimas">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/abdimas.png'); ?>"></span>
                    <span class="title">Abdimas</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='marcom'){echo 'current';} ?>"
                division="17"
                data-dpt="marcom">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/marcom.png'); ?>"></span>
                    <span class="title">Marcom</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='pu-x'){echo 'current';} ?>"
                division="19"
                data-dpt="pu-x">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/pu-x.png'); ?>"></span>
                    <span class="title">PU-X</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='pucel'){echo 'current';} ?>"
                division="20"
                data-dpt="pucel">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/pucel.png'); ?>"></span>
                    <span class="title">Pucel</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='yayasan'){echo 'current';} ?>"
                division="1"
                data-dpt="yayasan">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/yayasan.png'); ?>"></span>
                    <span class="title">Yayasan</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='learning-development-center'){echo 'current';} ?>"
                division="39"
                data-dpt="learning-development-center">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/ldc.png'); ?>"></span>
                    <span class="title">Learning & Development Center</span>
                </a>
            </li>
            <li class="departement <?php if($departement=='pu-press'){echo 'current';} ?>"
                division="40"
                data-dpt="pu-press">
                <a href="javascript:void(0);">
                    <span class="image"><img src="<?php echo base_url('assets/icon/press.png'); ?>"></span>
                    <span class="title">PU Press</span>
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
