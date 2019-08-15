<div class="col-xs-12" >
	<div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Template Approval</h4>
        </div>
        <div class="panel-body">
            <div class="tabbable tabbable-custom tabbable-full-width btn-read setTemplate">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="pageAnchorTemplate" page = "Master">Master</a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)" class="pageAnchorTemplate" page = "Transaksi">Transaksi</a>
                    </li>
                </ul>
                <div style="padding-top: 30px;border-top: 1px solid #cccccc">
                    <div class="row" id = "pageSetTemplate">
                       
                    </div>
                </div>
            </div>            
        </div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        LoadPage_template('Master');

        $(".pageAnchorTemplate").click(function(){
            var Page = $(this).attr('page');
            $(".setTemplate li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageSetTemplate").empty();
            LoadPage_template(Page);
        });
        
    }); // exit document Function

    function LoadPage_template(page)
    {
        loading_page("#pageSetTemplate");
        var url = base_url_js+'budgeting/config_pr/Set_Template/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            setTimeout(function () {
                $("#pageSetTemplate").empty();
                $("#pageSetTemplate").html(html);
            },1000);
            
        }); // exit spost
    }
</script>