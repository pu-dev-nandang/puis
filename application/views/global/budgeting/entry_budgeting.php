<style type="text/css">
    .Custom-UnitCost
    {
        width: 8.333% !important;
    }

    .Custom-PostBudget{
         /*width: 9.333% !important;*/
        width: 10.333% !important;
    }

    .Custom-Freq{
        /*width: 10.333% !important;*/
        width: 7.333% !important;
    }

    .Custom-Total{
        /*width: 13.666666666666664% !important;*/
        width: 8.666666666666664% !important;
    }

    .Custom-select2 {
        width: 85% !important;
    }

    .CustomSubtotalFooter {
        /*margin-left: 28%; !important;*/
        margin-left: 26%; !important;
        /* adding */
        width: 64%;
    }

    /* Custom for Report Anggaran*/
    #tableDataMapping tr {
        cursor: pointer;
    }
    .hiddenRow {
        padding: 0 4px !important;
        background-color: #eeeeee;
        font-size: 13px;
        /*padding: 15px !important;*/
    }


    #TblBudgetAllocation tbody {
        display:block;
        height:120px;
        overflow:auto;
    }
    #TblBudgetAllocation thead,#TblBudgetAllocation tfoot,#TblBudgetAllocation tbody tr {
        display:table;
        width:100%;
        table-layout:fixed; /* even columns width , fix width of table too*/
    }
    #TblBudgetAllocation thead,#TblBudgetAllocation tfoot {
        /*width: calc( 100% - 1em ) scrollbar is average 1em/16px width, remove it from thead width */
         width: calc( 100% - 1.3em )
    }
    /*#TblBudgetAllocation table {
        width:400px;
    }*/

    /*
        note col-md-7 64% di form_entry_budgeting adalah custom
    */
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menuEBudget">
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?php echo base_url().'/budgeting' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li>
        <?php //if ($this->session->userdata('IDDepartementPUBudget') != 'NA.9'): ?>
        <li class="<?php if($request=='EntryPostItemBudgeting'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "EntryPostItemBudgeting">Entry Sub Account</a>
        </li>
        <?php //endif ?>
        <li class="<?php if($request==null || $request=='EntryBudget'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "EntryBudget">Set Budget</a>
        </li>
        <?php if ($this->session->userdata('IDDepartementPUBudget') == 'NA.9'): ?>
        <li class="<?php if($request=='ListBudgetDepartement'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "ListBudgetDepartement">List Budget</a>
        </li>
        <li class="<?php if($request=='budget_revisi'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "budget_revisi">Budget Revisi</a>
        </li>
        <li class="<?php if($request=='report_anggaran_per_years'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "report_anggaran_per_years">Report Anggaran</a>
        </li>
        <?php endif ?>
        <li class="<?php if($request=='BudgetLeft'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "BudgetLeft">Budget Remaining</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
            <!-- <div class="panel panel-primary" style="min-width: 1600px;overflow: auto;"> -->
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Budget</h4>
                </div>
                <div class="panel-body">
                    <div class="row" id = "pageContent">
                       
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var DivSession = "<?php echo $this->session->userdata('IDDepartementPUBudget') ?>";
    $(document).ready(function() {
        $("#container").attr('class','fixed-header sidebar-closed');
        // $("#sidebar").remove();
        <?php if ($request == null): ?>
            LoadPage('EntryBudget');
        <?php else: ?>
           LoadPage("<?php echo $request ?>");    
        <?php endif ?>

        $(".pageAnchor").click(function(){
            var Page = $(this).attr('page');
            $(".menuEBudget li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageContent").empty();
            LoadPage(Page);
        });
        
    }); // exit document Function

    function LoadPage(page)
    {
        loadingStart();
        loading_page("#pageContent");
        if (jQuery("#pageInputApproval").length) {
          $("#pageInputApproval").remove();
        }
        var url = base_url_js+'budgeting/EntryBudget/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            if (page=='EntryBudget') {
                $('.panel-primary').attr('style','min-width: 1600px;overflow: auto;');
            }
            else
            {
                $('.panel-primary').attr('style','');
            }
            setTimeout(function () {
                $("#pageContent").empty();
                $("#pageContent").html(html);
            },1000);
            
        }); // exit spost
    }

    function load_budget_remaining__(Year,Departement)
    {
        var def = jQuery.Deferred();
        var url = base_url_js+"budgeting/detail_budgeting_remaining";
        var data = {
                    Year : Year,
                    Departement : Departement,
                };
        var token = jwt_encode(data,'UAP)(*');
        $.post(url,{token:token},function (resultJson) {
            
        }).done(function(resultJson) {
            var response2 = jQuery.parseJSON(resultJson);
            def.resolve(response2);
        }).fail(function() {
          toastr.info('No Result Data'); 
          def.reject();
        }).always(function() {
                        
        });
        return def.promise();
    }
</script>