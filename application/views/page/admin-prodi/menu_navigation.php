<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!--=== Navigation ===-->
        <ul id="nav">
            <!-- <li  class="dropdown <?php if($this->uri->segment(3,0)=='dashboard'){echo "current";} ?>">
                <a href="<?php echo base_url('dashboard'); ?>" class="dropdown-toggle" data-toggle="dropdown"> -->
            <li class="<?= ($this->uri->segment(2)=='beranda') ? 'current open' : ''; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-tachometer"></i>
                    Beranda
                    
                </a>
                <ul class="sub-menu">
                        <li class="<?php if($this->uri->segment(3,0)=='slide'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/slide'); ?>">
                                <i class="icon-angle-right"></i>
                                Slider
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='overview'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/overview'); ?>">
                                <i class="icon-angle-right"></i>
                                Greetings
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3)=='why-choose-us'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/why-choose-us/whychoose'); ?>">
                                <i class="icon-angle-right"></i>
                                Why Choose us?
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='calltoaction'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/calltoaction'); ?>">
                                <i class="icon-angle-right"></i>
                                Call to action
                                </a>
                        </li>
                       
                        <li class="<?php if($this->uri->segment(3,0)=='testimoni'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/testimoni'); ?>">
                                <i class="icon-angle-right"></i>
                                Testimoni
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='client'){echo "current";} ?>">
                                <a href="<?php echo base_url('prodi/beranda/client'); ?>">
                                <i class="icon-angle-right"></i>
                                Our Clients
                                </a>
                        </li>
                    </ul>
            </li>
            <li class="<?= ($this->uri->segment(2)=='tentang') ? 'current open' : ''; ?>">
                <a href="javascript:void(0);">
                    <i class="fa fa-users"></i>
                    Tentang
                    
                </a>
                <ul class="sub-menu">
                        <li class="<?php if($this->uri->segment(3,0)=='sambutan'){echo "current";} ?>">
                                <a href="<?php echo base_url('sambutan'); ?>">
                                <i class="icon-angle-right"></i>
                                Sambuatan
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='visi'){echo "current";} ?>">
                                <a href="<?php echo base_url('visi'); ?>">
                                <i class="icon-angle-right"></i>
                                Visi dan Misi
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='struktur'){echo "current";} ?>">
                                <a href="<?php echo base_url('sambutan'); ?>">
                                <i class="icon-angle-right"></i>
                                Strukture Organisasi
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='dosen'){echo "current";} ?>">
                                <a href="<?php echo base_url('dosen'); ?>">
                                <i class="icon-angle-right"></i>
                                Dosen
                                </a>
                        </li>
                        <li class="<?php if($this->uri->segment(3,0)=='fasilitas'){echo "current";} ?>">
                                <a href="<?php echo base_url('fasilitas'); ?>">
                                <i class="icon-angle-right"></i>
                                Fasilitas
                                </a>
                        </li>
                        
                    </ul>
            </li>
            <li class="<?php if($this->uri->segment(3,0)=='choose'){echo "penelitian";} ?>">
                <a href="<?php echo base_url('penelitian'); ?>">
                    <i class="fa fa-flask"></i>
                    Penelitian
                    
                </a>
            </li>
            <li class="<?php if($this->uri->segment(3,0)=='berita'){echo "current";} ?>">
                <a href="<?php echo base_url('berita'); ?>">
                    <i class="fa fa-newspaper-o"></i>
                    Berita
                    
                </a>
            </li>
            <li class="<?php if($this->uri->segment(3,0)=='mahasiswa'){echo "current";} ?>">
                <a href="<?php echo base_url('mahasiswa'); ?>">
                    <i class="fa fa-graduation-cap"></i>
                    Mahasiswa
                    
                </a>
            </li>
            <!-- <li>
                <a href="<?php echo base_url('Penelitian'); ?>">
                    <i class="fa fa-tachometer"></i>
                    Alumni
                    
                </a>
            </li> -->
            <li class="<?php if($this->uri->segment(3,0)=='galeri'){echo "current";} ?>">
                <a href="<?php echo base_url('galeri'); ?>">
                    <i class="fa fa-th-large"></i>
                    Galeri
                    
                </a>
            </li>
            <li class="<?php if($this->uri->segment(3,0)=='kontak'){echo "current";} ?>">
                <a href="<?php echo base_url('kontak'); ?>">
                    <i class="fa fa-phone"></i>
                    Kontak
                    
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
