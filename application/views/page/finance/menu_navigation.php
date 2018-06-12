<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">
            <li class="<?php if($this->uri->segment(2)=='master'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-globe"></i>
                    Master
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "tagihan-mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Tagihan Mahasiswa
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='approved'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-address-book-o"></i>
                    Approved
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='approved' && $this->uri->segment(3) == "nilai-rapor" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/approved/nilai-rapor'); ?>">
                        <i class="icon-angle-right"></i>
                        Nilai Rapor
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='approved' && $this->uri->segment(3) == "tuition-fee" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/approved/tuition-fee'); ?>">
                        <i class="icon-angle-right"></i>
                        Tuition Fee
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='penerimaan-pembayaran'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-download"></i>
                    Penerimaan Pembayaran
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='penerimaan-pembayaran' && $this->uri->segment(3) == "formulir-registration" ){echo "open-default";} ?>">
                        <a href="javascript:void(0);">
                            <i class="icon-angle-right"></i>
                            Formulir Registration
                        </a>
                        <ul class="sub-menu">
                            <li class="<?php if($this->uri->segment(2)=='penerimaan-pembayaran' && $this->uri->segment(3) == "formulir-registration" && $this->uri->segment(4) == "online"){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/penerimaan-pembayaran/formulir-registration/online'); ?>">
                                    <i class="icon-angle-right"></i>
                                    Online
                                </a>
                            </li>
                            <li class="<?php if($this->uri->segment(2)=='penerimaan-pembayaran' && $this->uri->segment(3) == "formulir-registration" && $this->uri->segment(4) == "offline"){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/penerimaan-pembayaran/formulir-registration/offline'); ?>">
                                    <i class="icon-angle-right"></i>
                                    Offline
                                </a>
                            </li>
                        </ul>    
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='penerimaan-pembayaran' && $this->uri->segment(3) == "admission" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/approved/tuition-fee'); ?>">
                        <i class="icon-angle-right"></i>
                        Admission
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='tagihan-mhs'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-money"></i>
                    Tagihan Mahasiswa
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "tagihan-mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Tagihan Mahasiswa
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    Tanggal Cair
                </a>
            </li>
            <li class="">
                <a href="#">
                    <i class="fa fa-refresh"></i>
                    Deposit Mahasiswa
                </a>
            </li>
            <li class="">
                <a href="#">
                    <i class="fa fa-user-secret"></i>
                    Mr. X
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


