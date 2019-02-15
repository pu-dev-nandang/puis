<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menuEBudget">
    <ul class="nav nav-tabs">
        <li role="presentation">
            <a href="<?php echo base_url().'/budgeting' ?>" style="padding:0px 15px">
                <button class="btn btn-primary" id="btnBackToHome" name="button"><i class="fa fa-home" aria-hidden="true"></i></button>
            </a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "catalog">Catalog</a>
        </li>
        <li class="active">
            <a href="javascript:void(0)" class="pageAnchor" page = "data">List PR</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "form">Entry PR</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="col-xs-12" >
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Purchase Requisition</h4>
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
    $(document).ready(function() {
        $("#container").attr('class','fixed-header sidebar-closed');
        $("#sidebar").remove();
        LoadPage('data');

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
        loading_page("#pageContent");
        var url = base_url_js+'budgeting/page_pr/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            setTimeout(function () {
                $("#pageContent").empty();
                $("#pageContent").html(html);
            },1000);
            
        }); // exit spost
    }
</script>
