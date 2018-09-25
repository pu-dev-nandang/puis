<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set User Role Departement</h4>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setUserRoleDepartement">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageAnchorUserRoleDepartement" page = "MasterUserRoleDepartement">Master</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchorUserRoleDepartement" page = "SetUserRoleDepartement">Set User Role</a>
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
    LoadMasterUserRoleDepartement();
    
}); // exit document Function

$(document).on('click','.pageAnchorUserRoleDepartement', function () {
    var Page = $(this).attr('page');
    $(".menuConfig li").removeClass('active');
    $(this).parent().addClass('active');
    $("#pageSetPostMenu").empty();
    switch(Page) {
        case "InputPostDepartement":
            LoadMasterUserRoleDepartement();
            break;
        case "LogPostDepartement":
            // LogPostDepartement();
            break;
        default:
            text = "I have never heard of that fruit...";
    }
});

function LoadMasterUserRoleDepartement()
{
    loading_page("#pageUserRoleDepartement");
    var url = base_url_js+'budgeting/page/LoadMasterUserRoleDepartement';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageUserRoleDepartement").html(html);
    }); // exit spost
}

function LogPostDepartement()
{
    loading_page("#pageSetPostMenu");
    var url = base_url_js+'budgeting/page/LogPostDepartement';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageSetPostMenu").html(html);
    }); // exit spost
}

function ExportPostDepartement()
{
    
}
</script>