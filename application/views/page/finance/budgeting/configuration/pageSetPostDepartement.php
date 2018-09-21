<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set Post Departement</h4>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setPostDepartementmenu">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageAnchor" page = "InputPostDepartement">Input</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pagePostDepartement" page = "LogPostDepartement">Log</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchor" page = "ExportPostDepartement">Export</a>
                    </li>
                </ul>
                <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                    <div class="row" id = "pageSetPostMenu">
                       
                    </div>
                </div>
            </div>            
        </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    LoadInputsetPostDepartement();
    
}); // exit document Function

function LoadInputsetPostDepartement()
{
    loading_page("#pageSetPostMenu");
    var url = base_url_js+'budgeting/page/LoadInputsetPostDepartement';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageSetPostMenu").html(html);
    }); // exit spost
}
</script>