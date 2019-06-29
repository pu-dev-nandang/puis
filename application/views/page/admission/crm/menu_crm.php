
<?php
    $ac = $this->uri->segment(2);
?>

<div class="tabbable tabbable-custom tabbable-full-width btn-read menuEBudget">
    <ul class="nav nav-tabs">
        <!--  <li role="presentation">
            <a href="<?php echo base_url().'/budgeting' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li> -->
        <li class="<?= ($ac=='') ? 'active' : ''; ?>">
            <a href="<?= base_url('crm'); ?>">Prospective Students</a>
        </li>
        <li class="<?= ($ac=='marketing-activity') ? 'active' : ''; ?>">
            <a href="<?= base_url('crm/marketing-activity'); ?>">Marketing Activity</a>
        </li>
        <li class="<?= ($ac=='crm-team') ? 'active'  : ''; ?>">
            <a href="<?= base_url('crm/crm-team'); ?>">CRM Team</a>
        </li>
        <li class="<?= ($ac=='contact') ? 'active'  : ''; ?>">
            <a href="<?= base_url('crm/contact'); ?>">Contact</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <?= $page; ?>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function () {
        getAdminCrm();
        window.adminPanel = '1';
    });
    
    function getAdminCrm() {

        var url = base_url_js+'rest/__getAdminCRM';
        $.getJSON(url,function (jsonResult) {

            if($.inArray(sessionNIP,jsonResult)==-1){

                adminPanel = '0';
                $('.panel-admin').remove();

            }

        });

    }
</script>

