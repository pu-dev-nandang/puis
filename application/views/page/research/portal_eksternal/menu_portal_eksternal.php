<script type="text/javascript" src="<?php echo base_url('js/research/portal-eksternal/Clas_global_portal_eksternal.js'); ?>"></script>
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?php if($this->uri->segment(1)=='research' || $this->uri->segment(2)=='portal-eksternal') { echo 'active'; } ?>">
            <a href="<?php echo base_url('research/portal-eksternal'); ?>">Portal Eksternal</a>
        </li>
    </ul>
    <div style="border-top: 1px solid #cccccc">
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4" id = "boxTotUser">
                        
                    </div>
                    <div class="col-md-4" id = "boxTotLoginToday">
                        
                    </div>
                    <div class="col-md-4" id = "boxTotApproval">
                       
                    </div>
                </div>
                <?php echo $page; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const rest_setting_global = <?php echo json_encode($rest_setting_global) ?>;
    const requestHeader = {
        Hjwtkey : rest_setting_global[0].Hjwtkey,
    }
    const Apikey = rest_setting_global[0].Apikey;
    const App_global_portal_eksternal = new Clas_global_portal_eksternal();
    $(document).ready(function(e){
        const selector1 = $('#boxTotUser');
        const selector2 = $('#boxTotLoginToday');
        const selector3 = $('#boxTotApproval');
        App_global_portal_eksternal.LoadDefault(selector1,selector2,selector3);
    })
</script>
