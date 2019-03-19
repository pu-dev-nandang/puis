<!-- include xprototype -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/custom/xprototype.js');?>"></script> -->
<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set User Role Department</h4>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setUserRoleDepartement">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageAnchorUserRoleDepartement" page = "SetUserActionDepartement">Set User Approval</a>
                    </li>
                </ul>
                <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                    <div id = "pageUserRoleDepartement">
                       
                    </div>
                </div>
            </div>            
        </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    LoadSetUserApprovalDepartement();

    $(".pageAnchorUserRoleDepartement").click(function(){
        var Page = $(this).attr('page');
        $(".setUserRoleDepartement li").removeClass('active');
        $(this).parent().addClass('active');
        $("#pageSetPostMenu").empty();
        switch(Page) {
            case "SetUserActionDepartement":
                LoadSetUserApprovalDepartement();
                break;
            default:
                text = "I have never heard of that fruit...";
        }
    });

}); // exit document Function

function LoadSetUserApprovalDepartement()
{
    loading_page("#pageUserRoleDepartement");
    var url = base_url_js+'budgeting/page/LoadSetUserApprovalDepartement';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageUserRoleDepartement").html(html);
    }); // exit spost
}

</script>