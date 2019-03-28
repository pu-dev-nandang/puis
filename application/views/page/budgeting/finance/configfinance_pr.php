<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div class="tabbable tabbable-custom tabbable-full-width btn-read menuConfig">
    <ul class="nav nav-tabs">
        <li class="<?php if($request==null || $request=='Set_Rad'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "Set_Rad">RAD</a>
        </li>
        <li class="<?php if($request=='Set_Approval'){echo "active";} ?>">
            <a href="javascript:void(0)" class="pageAnchor" page = "Set_Approval">Approval</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div id = "pageContent">
           
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        LoadPage('Set_Rad');

        $(".pageAnchor").click(function(){
            var Page = $(this).attr('page');
            $(".menuConfig li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageContent").empty();
            LoadPage(Page);
        });
        
    }); // exit document Function

    function LoadPage(page)
    {
        loading_page("#pageContent");
        var url = base_url_js+'budgeting/config_pr/'+page;
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