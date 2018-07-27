<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">

        <!--=== Navigation ===-->

        <ul id="nav">
            <!--<li class="<?php if($this->uri->segment(2)=='master'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                    Configuration
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='configuration' && $this->uri->segment(3) == "#" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/configuration/#'); ?>">
                        <i class="icon-angle-right"></i>
                        Bank
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='configuration' && $this->uri->segment(3) == "#" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/configuration/#'); ?>">
                        <i class="icon-angle-right"></i>
                        Prefix VA
                        </a>
                    </li>
                </ul>
            </li>-->
            <li class="<?php if($this->uri->segment(1)=='dashboard'){echo "current";} ?>">
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-tachometer"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='master'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-globe"></i>
                    Master
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "mahasiswa" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/mahasiswa'); ?>">
                        <i class="icon-angle-right"></i>
                        Mahasiswa
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "tagihan-mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Tagihan Mahasiswa
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "discount" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/discount'); ?>">
                        <i class="icon-angle-right"></i>
                        Discount
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "import_price_list_mhs" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/import_price_list_mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Import PriceList Mahasiswa
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='master' && $this->uri->segment(3) == "import_beasiswa_mahasiswa" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/master/import_beasiswa_mahasiswa'); ?>">
                        <i class="icon-angle-right"></i>
                        Import Beasiswa Mahasiswa
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='admission'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-address-book-o"></i>
                    Admission
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" ){echo "open-default";} ?>">
                        <a href="javascript:void(0);">
                        <i class="icon-angle-right"></i>
                        Approved
                        </a>
                        <ul class="sub-menu">
                            <!--<li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" && $this->uri->segment(4) == "nilai-rapor" && $this->uri->segment(5) == ""){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/approved/nilai-rapor'); ?>">
                                    <i class="icon-angle-right"></i>
                                    Nilai Rapor
                                </a>
                            </li>-->
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "approved" && $this->uri->segment(4) == "tuition-fee" ){echo "current";} ?>">
                                <a href="<?php echo base_url('finance/admission/approved/tuition-fee'); ?>">
                                <i class="icon-angle-right"></i>
                                Discount
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran"){echo "open-default";} ?>">
                        <a href="javascript:void(0);">
                        <i class="icon-angle-right"></i>
                        Penerimaan Pembayaran
                        </a>
                        <ul class="sub-menu">
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration"){echo "open-default";}  ?>">
                                <a href="javascript:void(0);">
                                    <i class="icon-angle-right"></i>
                                    Formulir Registration
                                </a>
                                <ul class="sub-menu">
                                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration" && $this->uri->segment(5) == "online"){echo "current";} ?>">
                                        <a href="<?php echo base_url('finance/admission/penerimaan-pembayaran/formulir-registration/online'); ?>">
                                            <i class="icon-angle-right"></i>
                                            Online
                                        </a>
                                    </li>
                                    <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "formulir-registration" && $this->uri->segment(5) == "offline"){echo "current";} ?>">
                                        <a href="<?php echo base_url('finance/admission/penerimaan-pembayaran/formulir-registration/offline'); ?>">
                                            <i class="icon-angle-right"></i>
                                            Offline
                                        </a>
                                    </li>
                                </ul>  
                            </li>
                            <li class="<?php if($this->uri->segment(2)=='admission' && $this->uri->segment(3) == "penerimaan-pembayaran" && $this->uri->segment(4) == "biaya" ){echo "current";} ?>">
                                <a href="#">
                                <i class="icon-angle-right"></i>
                                BPP,SPP,SKS & ETC
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='tagihan-mhs'){echo "current open";} ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-money"></i>
                    Tagihan Mahasiswa
                </a>
                <ul class="sub-menu">
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "import_pembayaran_manual" ){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/import_pembayaran_manual'); ?>">
                        <i class="icon-angle-right"></i>
                        Import Pembayaran Manual
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Tagihan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "set-cicilan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/set-cicilan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Set Cicilan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "edit-cicilan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/edit-cicilan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Edit / Delete Cicilan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "cancel-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/cancel-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Batal Tagihan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "cek-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/cek-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Daftar Tagihan
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "list-telat-bayar"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/list-telat-bayar'); ?>">
                        <i class="icon-angle-right"></i>
                        Daftar Outstanding Pembayaran
                        </a>
                    </li>
                    <li class="<?php if($this->uri->segment(2)=='tagihan-mhs' && $this->uri->segment(3) == "penerimaan-tagihan-mhs"){echo "current";} ?>">
                        <a href="<?php echo base_url('finance/tagihan-mhs/penerimaan-tagihan-mhs'); ?>">
                        <i class="icon-angle-right"></i>
                        Penerimaan Pembayaran
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?php if($this->uri->segment(2)=='check-va'){echo "current";} ?>">
                <a href="<?php echo base_url('finance/check-va'); ?>">
                    <i class="fa fa-refresh"></i>
                    Check VA
                </a>
            </li>
            <li class="<?php if($this->uri->segment(2)=='download-log-va'){echo "current";} ?>">
                <a href="<?php echo base_url('finance/download-log-va'); ?>">
                    <i class="fa fa-cloud-download"></i>
                    Download Log VA
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


