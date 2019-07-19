<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menu">
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?php echo base_url().'budgeting' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li>
        <?php if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9'): ?>
            <!-- <li class="<?php echo ($this->uri->segment(4) == 'configuration') ? 'active' : '' ?>">
                <a href="<?php echo base_url().'budgeting_menu/pembayaran/cashadvance/configuration'?>">Configuration</a>
            </li> -->
        <?php endif ?>
        <li class="<?php echo ($this->uri->segment(3) == 'cashadvance' &&  ($this->uri->segment(4) == '' || $this->uri->segment(4) == null)  ) ? 'active' : '' ?>">
            <a href="<?php echo base_url().'budgeting_menu/pembayaran/cashadvance'?>">List</a>
        </li>
        <li class="<?php echo ($this->uri->segment(4) == 'create_cashadvance') ? 'active' : '' ?>">
            <a href="<?php echo base_url().'budgeting_menu/pembayaran/cashadvance/create_cashadvance'?>">Create Cash Advance</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Cash Advance</h4>
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
        // loadingStart();
        // $("#container").attr('class','fixed-header sidebar-closed');
    }); // exit document Function
</script>