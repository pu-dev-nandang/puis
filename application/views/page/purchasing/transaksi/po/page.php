<style type="text/css">
    h3.header-blue {
        margin-top: 0px;
        border-left: 7px solid #2196F3;
        padding-left: 10px;
        font-weight: bold;
    }
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menu-po">
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?php echo base_url().'dashboard' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li>
        <li class="<?php echo ($this->uri->segment(5) == 'configuration') ? 'active' : '' ?>">
            <a href="<?php echo base_url().'purchasing/transaction/po/list/configuration'?>">Configuration</a>
        </li>
        <li class="<?php echo ($this->uri->segment(4) == 'list' &&  ($this->uri->segment(5) == '' || $this->uri->segment(5) == null)  ) ? 'active' : '' ?>">
            <a href="<?php echo base_url().'purchasing/transaction/po/list'?>">List</a>
        </li>
        <li class="<?php echo ($this->uri->segment(5) == 'open') ? 'active' : '' ?>">
            <a href="<?php echo base_url().'purchasing/transaction/po/list/open'?>">Open PO / SPK</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Purchase Order</h4>
                </div>
                <div class="panel-body">
                    <?php echo $content; ?>   
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        loadingStart();
        $("#container").attr('class','fixed-header sidebar-closed');
    }); // exit document Function
</script>