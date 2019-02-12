<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Set Post Department</h4>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setPostDepartementmenu">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageAnchorPostDepartement" page = "InputPostDepartement">Input</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchorPostDepartement" page = "LogPostDepartement">Log</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchorPostDepartement" page = "ExportPostDepartement">Export</a>
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
    // removejscssfile("<?php echo base_url('assets/custom/xprototype.js');?>", "js");
    // $('script[src="<?php echo base_url('assets/custom/xprototype.js');?>"]').remove()
    // replacejscssfile("<?php echo base_url('assets/custom/xprototype.js');?>", "newscript.js", "js")

    $(".pageAnchorPostDepartement").click(function(){
        var Page = $(this).attr('page');
        $(".setPostDepartementmenu li").removeClass('active');
        $(this).parent().addClass('active');
        $("#pageSetPostMenu").empty();
        switch(Page) {
            case "InputPostDepartement":
                LoadInputsetPostDepartement();
                break;
            case "LogPostDepartement":
                LogPostDepartement();
                break;
            case "ExportPostDepartement":
                ExportPostDepartement();
                break;
            default:
                text = "I have never heard of that fruit...";
        }
    })

}); // exit document Function

// $(document).on('click','.pageAnchorPostDepartement', function () {
    
// });

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
    loading_page("#pageSetPostMenu");
    var url = base_url_js+'budgeting/page/ExportPostDepartement';
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageSetPostMenu").html(html);
    }); // exit spost
}


$(document).on('click','#exportexcelpost',function () {
    var YearPostDepartement = $("#YearPostDepartement").val();
    var YearPostDepartementText = $("#YearPostDepartement option:selected").text();
    var DepartementPost = $("#DepartementPost").val();
    var DepartementPostName = $("#DepartementPost option:selected").text();
    var url = base_url_js+'budgeting/export_excel_post_department';
    data = {
      YearPostDepartement : YearPostDepartement,
      YearPostDepartementText : YearPostDepartementText,
      DepartementPost : DepartementPost,
      DepartementPostName : DepartementPostName,
    }
    var token = jwt_encode(data,"UAP)(*");
    FormSubmitAuto(url, 'POST', [
        { name: 'token', value: token },
    ]);
});
</script>