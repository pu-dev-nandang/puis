<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Rule & Service User</h4>
            <button onclick="testMobile()">Test Notive Mobile</button>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setRuleService">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageRuleService" page = "PageDivision">Division</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageRuleService" page = "PageService">Service</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageRuleService" page = "PageRuleService">Rule Service</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageRuleService" page = "PageRuleUser">Rule User</a>
                    </li>
                </ul>
                <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                    <div class="row" id = "pageSetRuleService" style="margin-left: 0px;margin-right: 0px">
                       
                    </div>
                </div>
            </div>            
        </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    LoadPage('PageDivision');
    $(".pageRuleService").click(function(){
        var Page = $(this).attr('page');
        $(".setRuleService li").removeClass('active');
        $(this).parent().addClass('active');
        LoadPage(Page)
    })

}); // exit document Function

function LoadPage(Page)
{
    $("#pageSetRuleService").empty();
    loading_page("#pageSetRuleService");
    var url = base_url_js+'it/rule_service/'+Page;
    $.post(url,function (resultJson) {
        var response = jQuery.parseJSON(resultJson);
        var html = response.html;
        var jsonPass = response.jsonPass;
        $("#pageSetRuleService").html(html);
    }); // exit spost
}
</script>