<div class="tabbable tabbable-custom tabbable-full-width btn-read menuCatalog">
    <ul class="nav nav-tabs">
        <li class="">
            <a href="javascript:void(0)" class="pageAnchorCatalog" page = "entry_catalog">Entry</a>
        </li>
        <li class="active">
            <a href="javascript:void(0)" class="pageAnchorCatalog" page = "datacatalog">List</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div id = "pageContentCatalog" style="margin-left: 10px;margin-right: 10px">
           
        </div> 
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        LoadPageCatalog('datacatalog');
        $(".pageAnchorCatalog").click(function(){
            var Page = $(this).attr('page');
            $(".menuCatalog li").removeClass('active');
            $(this).parent().addClass('active');
            $("#pageContentCatalog").empty();
            LoadPageCatalog(Page);
        });
    }); // exit document Function

    $(document).off('click', '.btn-add-new-catalog').on('click', '.btn-add-new-catalog',function(e) {
        var Page = $(this).attr('page');
        $(".menuCatalog li").removeClass('active');
        $('.pageAnchorCatalog[page="'+Page+'"]').parent().addClass('active');
        $("#pageContentCatalog").empty();
        LoadPageCatalog(Page);
    });

    function LoadPageCatalog(page)
    {
        loading_page("#pageContentCatalog");
        var url = base_url_js+'budgeting/page_pr_catalog/'+page;
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            setTimeout(function () {
                $("#pageContentCatalog").empty();
                $("#pageContentCatalog").html(html);
            },1000);
            
        }); // exit spost
    }
</script>