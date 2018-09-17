<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="javascript:void(0)" class="pageAnchor" page = "PrefixCode">Code Prefix</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "TimePeriod">Time Period</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "Item">Master Post</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "Item">Set Post Departement</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "MasterUserRole">Master User Role</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "UserRole">Set User Role Departement</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "Catalog">Catalog</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchor" page = "Catalog">Supplier</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div class="row" id = "pageContentConfig">
           
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        LoadCodePrefix();
    }); // exit document Function

    function LoadCodePrefix()
    {
        loading_page("#pageContentConfig");
        // setTimeout(function()
        //     { 
        //         $("#pageContentConfig").html('<div class = "row" align = "center"><h2>Comming Soon</h2></div>');
        //     }, 
        // 1000);
        $("#pageContentConfig").html('<div class = "row" align = "center"><h2>Comming Soon</h2></div>');
    }

    function LoadTimePeriod()
    {
        loading_page("#pageContentConfig");
        var url = base_url_js+'budgeting/page/LoadTimePeriod';
        $.post(url,function (resultJson) {
            var response = jQuery.parseJSON(resultJson);
            var html = response.html;
            var jsonPass = response.jsonPass;
            $("#pageContentConfig").html(html);
        }); // exit spost
    }

    $(document).on('click','.pageAnchor', function () {
        var Page = $(this).attr('page');
        $("li").removeClass('active');
        $(this).parent().addClass('active');
        $("#pageContentConfig").empty();
        switch(Page) {
            case "PrefixCode":
                LoadCodePrefix();
                break;
            case "TimePeriod":
                LoadTimePeriod();
                break;
            case "Apple":
                text = "How you like them apples?";
                break;
            default:
                text = "I have never heard of that fruit...";
        }
    });
</script>