<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menu-po">
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?php echo base_url().'dashboard' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li>
        <li class="<?php echo ($this->uri->segment(3) == 'master') ? 'active' : '' ?>">
            <a href="<?php echo base_url().'cooperation/kerjasama-perguruan-tinggi/master'?>">Kerja Sama</a>
        </li>
        <li class="<?php echo ($this->uri->segment(2) == 'kerjasama-perguruan-tinggi' && ( $this->uri->segment(3) == '' || $this->uri->segment(3) == null )  ) ? 'active' : '' ?>">
            <a href="<?php echo base_url().'cooperation/kerjasama-perguruan-tinggi'?>">Kegiatan</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Kerja Sama Perguruan Tinggi</h4>
                </div>
                <div class="panel-body">
                    <?php echo $content; ?>   
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#container").attr('class','fixed-header sidebar-closed');
</script>